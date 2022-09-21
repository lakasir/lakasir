<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/** @package App\Http\Controllers\Api\Master */
class ProductController extends Controller
{
    public function index(Request $request)
    {
        return $this->success(Product::filter($request)->get());
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->rules());
        $product = new Product();
        $product->fill($request->merge(['category_id' => category::findorfail($request->category)->id])->except('category'));
        $product->save();

        return $this->success([], "success creating items");
    }

    public function show(Product $product)
    {
        return $this->success($product->load('category'));
    }

    public function update(Request $request, Product $product)
    {
        $this->validate($request, $this->rules());
        $product->fill($request->merge(['category_id' => category::findorfail($request->category)->id])->except('category'));
        $product->update();

        return $this->success([], "success updating items");
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return $this->success([], "success deleting items");
    }

    private function rules(): array
    {
        return [
            "name" => ["required", "min:3"],
            "category" => ["required"],
            "stock" => ["numeric", "required", "min:0"],
            "initial_price" => ["numeric", "required", "lte:selling_price"],
            "selling_price" => ["numeric", "required", "gte:initial_price"],
            "type" => [Rule::in("product", "service"), "required"]
        ];
    }
}
