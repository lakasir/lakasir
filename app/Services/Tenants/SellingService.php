<?php

namespace App\Services\Tenants;

use App\Events\RecalculateEvent;
use App\Events\SellingCreated;
use App\Features\SellingTax;
use App\Models\Tenants\PaymentMethod;
use App\Models\Tenants\PriceUnit;
use App\Models\Tenants\Product;
use App\Models\Tenants\Selling;
use App\Models\Tenants\Setting;
use App\Services\Tenants\Traits\HasNumber;
use App\Services\VoucherService;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Laravel\Pennant\Feature;

class SellingService
{
    use HasNumber;

    /**
     * Persist a sale. $data must already hold the server-computed totals from
     * mapProductRequest(); the voucher's quota is only used up here, once the
     * sale is valid and inside the transaction.
     */
    public function create(array $data)
    {
        try {
            DB::beginTransaction();
            /** @var Selling $selling */
            $selling = Selling::create(Arr::only($data, [
                'date',
                'member_id',
                'customer_number',
                'payment_method_id',
                'table_id',
                'note',
                'voucher',
                'fee',
                'payed_money',
                'money_changes',
                'total_price',
                'total_cost',
                'total_qty',
                'total_discount_per_item',
                'discount_price',
                'tax',
                'tax_price',
            ]));
            $selling->friend_price = (bool) ($data['friend_price'] ?? false);
            $selling->save();

            if (! empty($data['voucher'])) {
                (new VoucherService())->applyable($data['voucher'], $data['voucher_base_price'] ?? 0)?->reduceUsed();
            }

            SellingCreated::dispatch($selling, $data);

            /** @var Collection<Product> $products */
            $products = Product::whereIn('id', $selling->sellingDetails->pluck('product_id'))->get();
            RecalculateEvent::dispatch($products, $data);

            DB::commit();

            return $selling;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Compute every price, discount and tax of a sale on the server.
     *
     * - Line prices come from the product (or its price unit); a client price is ignored.
     * - Discounts are the line discounts plus the order discount, or the voucher
     *   discount when a valid voucher is given (the voucher replaces the order discount).
     * - Tax uses the store's default_tax when the SellingTax feature is on, and is
     *   charged on the price after discounts.
     * - For a friend price sale the client total is the final amount; the
     *   difference from the normal amount due is recorded as order discount.
     *
     * total_price keeps its meaning: subtotal + tax. Amount due is
     * total_price - discount_price - total_discount_per_item.
     */
    public function calculate(array $data): array
    {
        $products = [];
        $subtotal = 0.0;
        $totalDiscountPerItem = 0.0;
        $totalCost = 0.0;

        foreach ($data['products'] ?? [] as $line) {
            $product = Product::find($line['product_id']);
            $qty = (float) $line['qty'];
            $priceUnit = ! empty($line['price_unit_id']) ? PriceUnit::find($line['price_unit_id']) : null;

            $price = $priceUnit
                ? (float) $priceUnit->selling_price * $qty
                : (float) $product->selling_price * $qty;
            $baseQty = $priceUnit ? (float) $priceUnit->stock * $qty : $qty;
            $discount = (float) ($line['discount_price'] ?? 0);

            $products[] = array_filter([
                'product_id' => $product->id,
                'qty' => $line['qty'],
                'price' => $price,
                'discount_price' => $discount,
                'price_unit_id' => $priceUnit?->id,
                'note' => $line['note'] ?? null,
            ], fn ($value) => $value !== null);

            $subtotal += $price;
            $totalDiscountPerItem += $discount;
            $totalCost += (float) $product->initial_price * $baseQty;
        }

        $discountPrice = (float) ($data['discount_price'] ?? 0);
        $voucherBasePrice = $subtotal - $totalDiscountPerItem;
        $voucherApplied = false;
        if (! empty($data['voucher'])) {
            $voucher = (new VoucherService())->applyable($data['voucher'], $voucherBasePrice);
            if ($voucher) {
                $discountPrice = $voucher->calculate();
                $voucherApplied = true;
            }
        }

        $taxRate = Feature::active(SellingTax::class) ? (float) Setting::get('default_tax', 0) : 0.0;
        $taxable = max(0, $subtotal - $totalDiscountPerItem - $discountPrice);
        $taxPrice = round($taxable * $taxRate / 100, 2);
        $totalPrice = $subtotal + $taxPrice;
        $amountDue = $taxable + $taxPrice;
        $normalAmountDue = $amountDue;

        $friendPrice = (bool) ($data['friend_price'] ?? false);
        if ($friendPrice && isset($data['total_price']) && is_numeric($data['total_price'])) {
            $discountPrice += $amountDue - (float) $data['total_price'];
            $amountDue = (float) $data['total_price'];
        }

        $payedMoney = (float) ($data['payed_money'] ?? 0);
        $paymentMethodId = $data['payment_method_id'] ?? PaymentMethod::where('name', 'Cash')->value('id');
        $paymentMethod = $paymentMethodId ? PaymentMethod::find($paymentMethodId) : null;

        return [
            'products' => $products,
            'subtotal' => $subtotal,
            'total_discount_per_item' => $totalDiscountPerItem,
            'discount_price' => $discountPrice,
            'voucher_applied' => $voucherApplied,
            'voucher_base_price' => $voucherBasePrice,
            'tax' => $taxRate,
            'tax_price' => $taxPrice,
            'total_price' => $totalPrice,
            'total_cost' => $totalCost,
            'total_qty' => collect($products)->sum('qty'),
            'normal_amount_due' => $normalAmountDue,
            'amount_due' => $amountDue,
            'payed_money' => $payedMoney,
            'money_changes' => $paymentMethod?->is_credit ? 0 : $payedMoney - $amountDue,
            'payment_method_id' => $paymentMethodId,
            'friend_price' => $friendPrice,
        ];
    }

    /**
     * The request data with the server-computed totals merged over it.
     */
    public function mapProductRequest(array $data): array
    {
        return array_merge($data, Arr::except($this->calculate($data), ['subtotal', 'voucher_applied']));
    }
}
