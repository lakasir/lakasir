<?php

namespace App\Http\Controllers\Api\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Selling;
use App\Rules\CheckProductStock;
use App\Rules\ShouldSameWithSellingDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SellingController extends Controller
{
    public function index(Request $request)
    {
        return $this->success(Selling::filter($request)->get());
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->rules());
        try {
            DB::beginTransaction();
            $selling = new Selling();
            $selling->fill(
                $request->merge([ 'member_id' => Member::find($request->member_id) ])
                        ->except('products')
            );
            $selling->save();
            DB::commit();
            return $this->success([], "success creating items");
        } catch (Exception $e) {
            DB::rollBack();
            return $this->fail([], $e->getMessage());
        }
    }

    public function show(Selling $selling)
    {
        return $this->success($selling);
    }

    public function update(Request $request, Selling $selling)
    {
        $this->validate($request, $this->rules());
    }

    public function destroy(Selling $selling)
    {
    }

    private function rules(): array
    {
        return [
            "payed_money" => ["required", "gte:total_price"],
            "total_price" => ["required", "lte:payed_money", new ShouldSameWithSellingDetail("price")],
            "total_qty" => ["required", new ShouldSameWithSellingDetail("qty")],
            "products" => ["array"],
            "products.*.product_id" => ["required"],
            "products.*.price" => ["required", "numeric"],
            "products.*.qty" => ["required", "numeric", "min:1", Rule::forEach(function ($value, $attribute)
            {
                $index = Str::of($attribute)->explode(".")->values()[1];
                return [
                    new CheckProductStock($index)
                ];
            })],
        ];
    }
}
