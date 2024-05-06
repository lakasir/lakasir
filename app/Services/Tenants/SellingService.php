<?php

namespace App\Services\Tenants;

use App\Events\SellingCreated;
use App\Models\Tenants\PaymentMethod;
use App\Models\Tenants\Product;
use App\Models\Tenants\Selling;
use Exception;
use Illuminate\Support\Facades\DB;

class SellingService
{
    public function create(array $data)
    {
        try {
            DB::beginTransaction();
            $selling = Selling::create($data);

            SellingCreated::dispatch($selling, $data);

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
        if (isset($data['friend_price'])) {
            $total_price = 0;
            $total_net_price = 0;
            Product::whereIn('id', collect($data['products'])->pluck('product_id'))->chunk(100,
                function ($products) use (&$total_price, &$total_net_price, $data) {
                    foreach ($products as $product) {
                        $total_price += $product->selling_price * collect($data['products'])
                            ->where('product_id', $product->id)
                            ->sum('qty');
                        $total_net_price += $product->initial_price * collect($data['products'])
                            ->where('product_id', $product->id)
                            ->sum('qty');
                    }
                });
            $total_price = ($tax_price = $total_price * ($data['tax'] ?? 0) / 100) + $total_price;
            $total_qty = collect($data['products'])->sum('qty');
            $request = [
                'total_price' => $total_price,
                'total_cost' => $total_net_price,
                'total_qty' => $total_qty,
                'money_change' => $data['payed_money'] - $total_price,
                'tax_price' => $tax_price,
            ];
        } else {
            $request = [
                'money_change' => $data['payed_money'] - $data['total_price'],
            ];
        }

        if (! isset($data['payment_method_id'])) {
            $request = array_merge($request, [
                'payment_method_id' => PaymentMethod::where('name', 'Cash')->first()->id,
            ]);
        }

        return $request;
    }
}
