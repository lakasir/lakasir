<?php

namespace App\Http\Requests;

use App\Models\Tenants\Member;
use App\Models\Tenants\Product;
use App\Models\Tenants\Selling;
use App\Rules\CheckProductStock;
use App\Rules\ShouldSameWithSellingDetail;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class TransactionSellingStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if (!$this->friend_price) {
            $total_price = 0;
            Product::whereIn("id", collect($this->products)->pluck("product_id"))->chunk(100, 
                function ($products) use (&$total_price) {
                foreach ($products as $product) {
                    $total_price += $product->selling_price * collect($this->products)
                            ->where("product_id", $product->id)
                            ->sum("qty");
                }
            });
            $total_qty = collect($this->products)->sum("qty");
            $this->merge([
                "total_price" => $total_price,
                "total_qty" => $total_qty,
                "money_change" => $this->payed_money - $total_price,
            ]);
        } else {
            $this->merge([
                "money_change" => $this->payed_money - $this->total_price,
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
                $this->merge(['member_id' => Member::find($this->member_id)])
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
