<?php

namespace App\Http\Controllers\Api\Tenants\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenants\Master\ProductRequest;
use App\Http\Resources\ProductCollection;
use App\Models\Tenants\Product;
use Spatie\QueryBuilder\QueryBuilder;

/** @package App\Http\Controllers\Api\Master */
class ProductController extends Controller
{
    public function index()
    {
        $products = QueryBuilder::for(Product::class)
            ->allowedFilters(['name', 'category_id', 'price', 'type'])
            ->allowedIncludes(['category', 'images'])
            ->orderByDesc('created_at')
            ->simplePaginate();

        return $this->buildResponse()
            ->setData(ProductCollection::collection($products))
            ->present();
    }

    public function store(ProductRequest $request)
    {
        $request->created();

        return $this->buildResponse()
            ->setMessage("success creating items")
            ->present();
    }

    public function show(Product $product)
    {
        $product = new ProductCollection($product);

        return $this->buildResponse()
            ->setData($product)
            ->present();
    }

    public function update(ProductRequest $request)
    {
        $request->updated();

        return $this->buildResponse()
            ->setMessage("success updating items")
            ->present();
    }

    public function destroy(Product $product, ProductRequest $request)
    {
        $request->deleteImages();
        $product->delete();

        return $this->buildResponse()
            ->setMessage("success deleting items")
            ->present();
    }
}
