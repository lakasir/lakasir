<?php

namespace App\Http\Controllers\Api\Tenants\Master\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenants\Master\StockRequest;
use App\Models\Tenants\Product;
use App\Models\Tenants\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Product $product)
    {
        $stocks = $product->stocks()
        ->orderByDesc('created_at')
        ->simplePaginate();

        return $this->buildResponse()
            ->setData($stocks)
            ->present();
    }

    public function store(StockRequest $request, Product $product)
    {
        $request->store();

        return $this->buildResponse()
            ->setMessage('success creating stock for ' . $product->name)
            ->present();
    }
}
