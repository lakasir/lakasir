<?php

namespace App\Observers;

use App\Features\ProductStock;
use App\Models\Tenants\Product;
use App\Services\Tenants\StockService;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Lakasir\LakasirModule\Events\ProductCreated;
use Lakasir\LakasirModule\Events\ProductUpdated;
use Laravel\Pennant\Feature;

/**
 * Fills in what a new product needs on top of the insert: a sku, a fallback
 * barcode, and the opening stock row. It holds no state of its own, so it is
 * safe under a long-running worker.
 *
 * Callers that want extra barcodes attach them to the product before saving
 * (`$product->barcodes`), or use ProductService which does it for them.
 */
class ProductObserver extends AbstractObserver implements DataAwareRule
{
    protected $data = [];

    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Three letters of the category, three of the name, then the id. A product
     * without a category gets GEN so the sku still exists.
     */
    private function prefix(Product $product): string
    {
        $category = Str::of($product->category?->name ?? 'GEN')->substr(0, 3)->upper();

        return $category.'-'.Str::of($product->name)->substr(0, 3)->upper().'-';
    }

    public function creating(Product $product): void
    {
        if ($product->expired != null) {
            $this->data = ['expired' => $product->expired];
            $product->expired = null;
        }
    }

    public function created(Product $product)
    {
        $dirty = false;

        if (! Feature::active(ProductStock::class) && ! $product->is_non_stock) {
            $product->is_non_stock = true;
            $dirty = true;
        }

        if (! $product->sku) {
            $product->sku = $this->prefix($product).Str::of($product->id)->padLeft(4, 0)->value();
            $dirty = true;
        }

        if ($dirty) {
            // saveQuietly: no second round of events, and the row is written
            // once instead of the observer's save() after the insert.
            $product->saveQuietly();
        }

        // The caller's own barcodes (attached before save, or written by
        // ProductService) win; the generated one is only a fallback.
        if (! $product->barcodes()->exists()) {
            $product->barcodes()->create([
                'code' => $this->prefix($product).Str::of($product->id)->padLeft(4, 0)->value(),
                'type' => 'primary',
                'description' => __('Auto-generated code'),
                'is_active' => true,
            ]);
        }

        (new StockService)->create([
            'product_id' => $product->getKey(),
            'stock' => $product->stock ?? 0,
            'init_stock' => $product->stock ?? 0,
            'initial_price' => $product->initial_price ?? 0,
            'selling_price' => $product->selling_price,
            'type' => 'in',
            'expired' => $this->data['expired'] ?? null,
            'is_ready' => true,
        ]);

        Event::dispatch(new ProductCreated($product));
    }

    public function updated(Product $product): void
    {
        Event::dispatch(new ProductUpdated($product));
    }

}
