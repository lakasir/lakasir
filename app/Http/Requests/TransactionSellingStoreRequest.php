<?php

namespace App\Http\Requests;

use App\Models\Tenants\CashDrawer;
use App\Models\Tenants\Member;
use App\Models\Tenants\PaymentMethod;
use App\Models\Tenants\Product;
use App\Models\Tenants\Selling;
use App\Models\Tenants\Setting;
use App\Rules\CheckProductStock;
use App\Rules\ShouldSameWithSellingDetail;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TransactionSellingStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        if(Setting::get('cash_drawer_enabled', false)) {
            $lastOpenedCashDrawer = CashDrawer::lastOpened()->first();
            if (!$lastOpenedCashDrawer) {
                throw ValidationException::withMessages([
                    'cash_drawer' => 'Cash drawer is not opened',
                ]);
            }
        }

        return true;
    }

    public function rules(): array
    {
        if (!$this->friend_price) {
            $total_price = 0;
            $total_net_price = 0;
            Product::whereIn("id", collect($this->products)->pluck("product_id"))->chunk(100,
                function ($products) use (&$total_price, &$total_net_price) {
                foreach ($products as $product) {
                    $total_price += $product->selling_price * collect($this->products)
                            ->where("product_id", $product->id)
                            ->sum("qty");
                    $total_net_price += $product->initial_price * collect($this->products)
                            ->where("product_id", $product->id)
                            ->sum("qty");
                }
            });
            $total_qty = collect($this->products)->sum("qty");
            $this->merge([
                "total_price" => $total_price,
                "total_net_price" => $total_net_price,
                "total_qty" => $total_qty,
                "money_change" => $this->payed_money - $total_price,
            ]);
        } else {
            $this->merge([
                "money_change" => $this->payed_money - $this->total_price,
            ]);
        }

        if (!$this->payment_method_id) {
            $this->merge([
                "payment_method_id" => PaymentMethod::where("name", "Cash")->first()->id
            ]);
        }


        return [
            "payed_money" => ["required", "gte:total_price"],
            "total_price" => ["required_if:friend_price,true", "numeric"],
            "total_qty" => ["required_if:friend_price,true", "numeric", new ShouldSameWithSellingDetail("qty")],
            "friend_price" => ["required", "boolean"],
            "products" => ["array"],
            "products.*.product_id" => ["required", "exists:products,id"],
            "products.*.price" => ["required_if:friend_price,true", "numeric"],
            "products.*.qty" => ["required", "numeric", "min:1", new CheckProductStock],
        ];
    }

    public function store(): void
    {
        try {
            DB::beginTransaction();
            $selling = new Selling();
            $selling->fill(
                $this->merge([
                    'member_id' => $this->member_id ? Member::findOrFail($this->member_id)->id : null,
                ])
                ->except('products')
            );
            $selling->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
