<?php

namespace App\Services\Tenants;

use App\Events\RecalculateEvent;
use App\Events\SellingCreated;
use App\Features\Discount;
use App\Features\SellingTax;
use App\Models\Tenants\CashDrawer;
use App\Models\Tenants\PaymentMethod;
use App\Models\Tenants\PriceUnit;
use App\Models\Tenants\Product;
use App\Models\Tenants\Selling;
use App\Models\Tenants\Setting;
use App\Rules\CheckProductStock;
use App\Services\Tenants\Traits\HasNumber;
use App\Services\VoucherService;
use Exception;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Pennant\Feature;

class SellingService
{
    use HasNumber;

    /**
     * Persist a sale. $data must already hold the server-computed totals from
     * calculate(); the voucher's quota is only used up here, once the
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
     * The one checkout entry point for callers that are not an HTTP request (the web cashier).
     * It runs the same rules and total checks as the API (TransactionSellingStoreRequest)
     * and stores the sale. The cashier is the authenticated user (see SellingObserver).
     *
     * @throws ValidationException
     */
    public function checkout(array $input): Selling
    {
        $this->ensureCashDrawerIsOpen();

        $totals = null;
        $validator = Validator::make($input, $this->rules($input));
        $validator->after(function (ValidatorContract $validator) use ($input, &$totals): void {
            $totals = $this->validateTotals($validator, $input);
        });

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $this->create(array_merge($input, $totals ?? $this->calculate($input)));
    }

    /**
     * A sale cannot be made while the cash drawer is enabled but not opened.
     *
     * @throws ValidationException
     */
    public function ensureCashDrawerIsOpen(): void
    {
        if (Setting::get('cash_drawer_enabled', false) && ! CashDrawer::lastOpened()->exists()) {
            throw ValidationException::withMessages([
                'cash_drawer' => 'Cash drawer is not opened',
            ]);
        }
    }

    /**
     * The checkout input rules shared by the API and the web cashier.
     *
     * A credit sale (a payment method with is_credit) becomes a receivable, which
     * belongs to a member and has a due date, so both are required for it.
     */
    public function rules(array $input): array
    {
        $isCredit = (bool) $this->paymentMethod($input['payment_method_id'] ?? null)?->is_credit;

        return [
            'fee' => ['numeric'],
            'payment_method_id' => ['nullable', 'exists:payment_methods,id'],
            'member_id' => [Rule::requiredIf($isCredit), 'nullable', 'exists:members,id'],
            'due_date' => [Rule::requiredIf($isCredit), 'nullable', 'date'],
            'payed_money' => ['nullable', 'numeric', 'min:0'],
            'friend_price' => ['required', 'boolean'],
            'total_price' => ['required_if:friend_price,true', 'numeric', 'min:0'],
            'note' => ['nullable', 'string', 'required_if:friend_price,true'],
            'discount_price' => ['nullable', 'numeric', 'min:0'],
            'voucher' => ['nullable', 'string'],
            'expected_total' => ['nullable', 'numeric'],
            'products' => ['required', 'array', 'min:1'],
            'products.*.product_id' => ['required', 'exists:products,id'],
            'products.*.qty' => ['required', 'numeric', 'min:1', new CheckProductStock],
            'products.*.discount_price' => ['nullable', 'numeric', 'min:0'],
            'products.*.price_unit_id' => ['nullable', 'exists:price_units,id'],
            'products.*.note' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * The checks that need the server totals, run after rules() pass. Adds its errors to
     * the validator and returns the totals, or null when the input shape is invalid.
     */
    public function validateTotals(ValidatorContract $validator, array $input): ?array
    {
        if ($validator->errors()->isNotEmpty()) {
            return null;
        }

        $this->validatePriceUnits($validator, $input);
        $this->validateDiscountsAllowed($validator, $input);
        if ($validator->errors()->isNotEmpty()) {
            return null;
        }

        $totals = $this->calculate($input);

        foreach ($totals['products'] as $i => $line) {
            if ($line['discount_price'] > $line['price']) {
                $validator->errors()->add("products.$i.discount_price", __('The discount cannot be more than the line price.'));
            }
        }

        if (! empty($input['voucher']) && ! $totals['voucher_applied']) {
            $validator->errors()->add('voucher', __('voucher expired'));
        }

        if ($totals['total_discount_per_item'] + $totals['discount_price'] > $totals['subtotal'] && ! $totals['friend_price']) {
            $validator->errors()->add('discount_price', __('The discount cannot be more than the subtotal.'));
        }

        if ($totals['friend_price']) {
            $this->validateFriendPrice($validator, $input, $totals);
        }

        if (! $this->paymentMethod($totals['payment_method_id'])?->is_credit && $totals['payed_money'] < $totals['amount_due']) {
            $validator->errors()->add('payed_money', __('validation.gte.numeric', [
                'attribute' => 'payed money',
                'value' => $totals['amount_due'],
            ]));
        }

        $expectedTotal = $input['expected_total'] ?? null;
        if ($expectedTotal !== null && $expectedTotal !== '' && abs((float) $expectedTotal - $totals['amount_due']) > 0.009) {
            $validator->errors()->add('expected_total', __('The total does not match the server total.'));
        }

        return $totals;
    }

    private function paymentMethod(mixed $id): ?PaymentMethod
    {
        $id = $id ?: PaymentMethod::where('name', 'Cash')->value('id');

        return $id && is_scalar($id) ? PaymentMethod::find($id) : null;
    }

    private function validatePriceUnits(ValidatorContract $validator, array $input): void
    {
        foreach ($input['products'] ?? [] as $i => $line) {
            if (empty($line['price_unit_id'])) {
                continue;
            }

            $belongs = PriceUnit::whereKey($line['price_unit_id'])
                ->where('product_id', $line['product_id'])
                ->exists();

            if (! $belongs) {
                $validator->errors()->add("products.$i.price_unit_id", __('The price unit does not belong to this product.'));
            }
        }
    }

    private function validateDiscountsAllowed(ValidatorContract $validator, array $input): void
    {
        if (Feature::active(Discount::class)) {
            return;
        }

        if ((float) ($input['discount_price'] ?? 0) > 0) {
            $validator->errors()->add('discount_price', __('Discounts are turned off for this store.'));
        }

        foreach ($input['products'] ?? [] as $i => $line) {
            if ((float) ($line['discount_price'] ?? 0) > 0) {
                $validator->errors()->add("products.$i.discount_price", __('Discounts are turned off for this store.'));
            }
        }
    }

    private function validateFriendPrice(ValidatorContract $validator, array $input, array $totals): void
    {
        $customTotal = (float) ($input['total_price'] ?? 0);

        if ($customTotal > $totals['normal_amount_due']) {
            $validator->errors()->add('total_price', __('A friend price cannot be more than the normal total.'));
        }

        if ($totals['total_cost'] > 0 && $customTotal < $totals['total_cost']) {
            $validator->errors()->add('total_price', __('A friend price cannot be below the cost price.'));
        }
    }
}
