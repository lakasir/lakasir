<?php

namespace App\Http\Controllers\Api\Tenants\Master\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenants\Master\StockRequest;
use App\Http\Resources\StockCollection;
use App\Models\Tenants\Product;

class StockController extends Controller
{
    public function index(Product $product)
    {
        $stocks = $product->stocks()
            ->orderByDesc('created_at')
            ->simplePaginate();

        return $this->buildResponse()
            ->setData(StockCollection::collection($stocks))
            ->present();
    }

    public function store(StockRequest $request, Product $product)
    {
        $request->store();

        return $this->buildResponse()
            ->setMessage('success creating stock for ' . $product->name)
            ->present();
    }

    public function destroy(Product $product)
    {
        $product->stocks()->delete();

        return $this->buildResponse()
            ->setMessage('success deleting stock for ' . $product->name)
            ->present();
    }
}
