<?php

namespace App\Listeners;

use App\Events\SellingCreated;
use App\Models\Tenants\Product;
use App\Models\Tenants\Selling;
use App\Models\Tenants\SellingDetail;
use App\Services\Tenants\StockService;

class AssignProduct
{
    public function __construct(public StockService $stockService)
    {
    }

    /**
     * Handle the event.
     */
    public function handle(SellingCreated $event): void
    {
        $this->assignTheProducts($event->selling, $event->data);
    }

    private function assignTheProducts(Selling $selling, array $data)
    {
        foreach ($data['products'] as $productRequest) {
            $product = Product::find($productRequest['product_id']);
            if (! $product->is_non_stock) {
                $this->reduceStock($product, $productRequest['qty']);
            }
            if (! $data['friend_price']) {
                $productRequest['price'] = $product->selling_price * $productRequest['qty'];
                $productRequest['cost'] = $product->initial_price * $productRequest['qty'];
            }
            $sellingDetail = new SellingDetail();
            $sellingDetail->fill($productRequest);
            $selling->sellingDetails()->save($sellingDetail);
            $sellingDetail->save();
        }
    }

    private function reduceStock(Product $product, $qty)
    {
        $this->stockService->reduceStock($product, $qty);
    }
}
