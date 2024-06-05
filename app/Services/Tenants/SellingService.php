<?php

namespace App\Services\Tenants;

use App\Events\RecalculateEvent;
use App\Events\SellingCreated;
use App\Models\Tenants\PaymentMethod;
use App\Models\Tenants\Product;
use App\Models\Tenants\Selling;
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
        $request = [];
        $payed_money = ($data['payed_money'] ?? 0);
        if (isset($data['friend_price']) && ! $data['friend_price']) {
            $total_price = 0;
            $total_price_after_discount = 0;
            $total_net_price = 0;
            Product::whereIn('id', collect($data['products'])->pluck('product_id'))->chunk(100,
                function ($products) use (&$total_price, &$total_net_price, $data, &$total_price_after_discount) {
                    foreach ($products as $product) {
                        $total_price += $product->selling_price * collect($data['products'])
                            ->where('product_id', $product->id)
                            ->sum('qty');
                        $total_price_after_discount = $total_price - collect($data['products'])->filter(function ($_product) use (&$product) {
                            return $_product['product_id'] === $product->id;
                        })[0]['discount_price'];
                        $total_net_price += $product->initial_price * collect($data['products'])
                            ->where('product_id', $product->id)
                            ->sum('qty');
                    }
                });
            $total_price = ($tax_price = $total_price * ($data['tax'] ?? 0) / 100) + $total_price;
            $total_qty = collect($data['products'])->sum('qty');
            $discount_price = 0;
            if ($data['voucher'] ?? false) {
                $voucherService = new VoucherService();
                if ($voucher = $voucherService->applyable($data['voucher'], $total_price)) {
                    $discount_price = $voucher->calculate();
                    $total_price = $total_price - $discount_price;
                    $voucher->reduceUsed();
                }
            }
            $request = [
                'discount_price' => $discount_price,
                'total_price' => $total_price,
                'total_cost' => $total_net_price,
                'total_qty' => $total_qty,
                'money_change' => $payed_money - $total_price,
                'tax_price' => $tax_price,
                'payed_money' => $payed_money,
            ];
        } else {
            $request = [
                'money_change' => ($data['payed_money'] ?? 0) - $data['total_price'],
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
                $request['money_change'] = 0;
            }
        }

        return $request;
    }
}
