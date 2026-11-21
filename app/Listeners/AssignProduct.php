<?php

namespace App\Listeners;

use App\Events\SellingCreated;
use App\Models\Tenants\PriceUnit;
use App\Models\Tenants\Product;
use App\Models\Tenants\Selling;
use App\Models\Tenants\SellingDetail;
use App\Services\Tenants\CheckoutProducts;
use App\Services\Tenants\StockService;
use Illuminate\Support\Arr;

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
        // The checkout already loaded the cart's products and price units.
        $catalog = app(CheckoutProducts::class);
        $catalog->loadProducts($data['products']);

        foreach ($data['products'] as $productRequest) {
            $priceUnit = $catalog->priceUnit(
                empty($productRequest['price_unit_id']) ? null : (int) $productRequest['price_unit_id'],
            );

            /** @var Product $product */
            $product = $catalog->product((int) $productRequest['product_id']);
            if (! $product->is_non_stock) {
                if ($priceUnit) {
                    $this->reduceStock($product, $priceUnit->stock * $productRequest['qty']);
                } else {
                    $this->reduceStock($product, $productRequest['qty']);
                }
            }

            if ($priceUnit) {
                $productRequest['price'] = $priceUnit->selling_price * $productRequest['qty'];
                $productRequest['qty'] = $priceUnit->stock * $productRequest['qty'];
            } else {
                $productRequest['price'] = ($productRequest['price'] ?? $product->selling_price * $productRequest['qty']);
            }
            $productRequest['cost'] = $product->initial_price * $productRequest['qty'];

            $sellingDetail = new SellingDetail();
            $sellingDetail->fill(Arr::only($productRequest, [
                'product_id',
                'qty',
                'price',
                'discount_price',
                'price_unit_id',
                'cost',
                'note',
            ]));
            // save() through the relation already inserts the row.
            $selling->sellingDetails()->save($sellingDetail);
        }
    }

    private function reduceStock(Product $product, float|int $qty): void
    {
        $this->stockService->reduceStock($product, (float) $qty);
    }
}
