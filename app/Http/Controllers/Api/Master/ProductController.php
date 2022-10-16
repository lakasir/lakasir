<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

/** @package App\Http\Controllers\Api\Master */
class ProductController extends Controller
{
    public function index(Request $request)
    {
        return $this->success(Product::with('images:name,url,id,product_id')->filter($request)->get());
    }

    public function store(ProductRequest $request)
    {
        try {
            $request->created();
            return $this->success([], "success creating items");
        } catch (\Exception $e) {
            return $this->fail([], $e->getMessage(), $e->getCode());
        }
    }

    public function show(Product $product)
    {
        return $this->success($product->load('category', 'images'));
    }

    public function update(ProductRequest $request)
    {
        try {
            $request->updated();
            return $this->success([], "success updating items");
        } catch (\Exception $e) {
            return $this->fail([], $e->getMessage(), $e->getCode());
        }
    }

    public function destroy(Product $product, ProductRequest $request)
    {
        $request->deleteImages();
        $product->delete();

        return $this->success([], "success deleting items");
    }
}
