<?php

namespace App\Services\Tenants;

use App\Events\RecalculateEvent;
use App\Events\SellingCreated;
use App\Models\Tenants\PaymentMethod;
use App\Models\Tenants\PriceUnit;
use App\Models\Tenants\Product;
use App\Models\Tenants\Selling;
use App\Models\Tenants\Setting;
use App\Services\Tenants\Traits\HasNumber;
use App\Services\VoucherService;
use Exception;
use Illuminate\Support\Facades\DB;

class SellingService
{
    use HasNumber;

    public function create(array $data)
    {
        try {
            DB::beginTransaction();
            /** @var Selling $selling */
            $selling = Selling::create($data);

            SellingCreated::dispatch($selling, $data);

            /** @var Collection<Product> $products */
            $products = Product::find($selling->sellingDetails->pluck('product_id'));
            RecalculateEvent::dispatch($products, $data);

            DB::commit();

            return $selling;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function mapProductRequest(array $data): array
    {
        if (Setting::get('default_tax', 0) != 0 && !isset($data['tax'])) {
            $data['tax'] = Setting::get('default_tax');
        }
        $request = [];
        $payed_money = ($data['payed_money'] ?? 0);
        if (isset($data['friend_price']) && ! $data['friend_price']) {
            $total_price = 0;
            $total_price_after_discount = 0;
            $total_discount_per_item = 0;
            $total_cost = 0;
            $productsCollection = collect($data['products']);
            $productsCollection->each(
                function ($product) use (&$total_price, &$total_cost, &$total_price_after_discount, &$total_discount_per_item) {
                    if (isset($product['price_unit_id']) && $product['price_unit_id'] != null) {
                        $product['price'] = PriceUnit::whereId($product['price_unit_id'])->first()->selling_price * $product['qty'];
                    }
                    $modelProduct = Product::find($product['product_id']);
                    $total_price += $product['price'] ?? $modelProduct->selling_price * $product['qty'];
                    $total_discount_per_item += ($product['discount_price'] ?? 0);
                    $total_price_after_discount = $total_price - ($product['discount_price'] ?? 0);
                    $total_cost += $modelProduct->initial_price * $product['qty'];
                }
            );
            $total_price = ($tax_price = $total_price * ($tax = $data['tax'] ?? 0) / 100) + $total_price;
            $total_qty = collect($data['products'])->sum('qty');
            $discount_price = $data['discount_price'] ?? 0;
            if ($data['voucher'] ?? false) {
                $voucherService = new VoucherService();
                if ($voucher = $voucherService->applyable($data['voucher'], $total_price)) {
                    $discount_price = $voucher->calculate();
                    // $total_price = $total_price - $discount_price;
                    $voucher->reduceUsed();
                }
            }
            $request = [
                'discount_price' => $discount_price,
                'total_price' => $total_price,
                'total_cost' => $total_cost,
                'total_qty' => $total_qty,
                'money_changes' => $payed_money - ($total_price - $discount_price - $total_discount_per_item),
                'total_discount_per_item' => $total_discount_per_item,
                'tax_price' => $tax_price,
                'tax' => $tax,
                'payed_money' => $payed_money,
            ];
        } else {
            $request = [
                'money_changes' => ($data['payed_money'] ?? 0) - $data['total_price'],
                'payed_money' => $payed_money,
            ];
        }

        if (! isset($data['payment_method_id'])) {
            $request = array_merge($request, [
                'payment_method_id' => PaymentMethod::where('name', 'Cash')->first()->id,
            ]);
        } else {
            /** @var PaymentMethod $pMethod */
            $pMethod = PaymentMethod::find($data['payment_method_id']);
            if ($pMethod->is_credit) {
                $request['money_changes'] = 0;
            }
        }

        return $request;
    }
}
