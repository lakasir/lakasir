<?php

namespace App\Services\Tenants;

use App\Models\Tenants\PriceUnit;
use App\Models\Tenants\Product;

/**
 * Products and price units for one checkout, looked up once.
 *
 * A sale asks for the same product in the validation rule, the total
 * calculation and the detail writer; without this each of them queries it
 * again (4 queries per cart line for a 10-line cart). Registered as a scoped
 * singleton (see AppServiceProvider), so one instance serves the request and
 * none outlives it.
 */
class CheckoutProducts
{
    /**
     * @var array<int, Product|null>
     */
    private array $products = [];

    /**
     * @var array<int, PriceUnit|null>
     */
    private array $priceUnits = [];

    /**
     * @var array<int, true>
     */
    private array $loadedProducts = [];

    public function product(int $id): ?Product
    {
        if (! array_key_exists($id, $this->loadedProducts)) {
            $this->products[$id] = Product::find($id);
            $this->loadedProducts[$id] = true;
        }

        return $this->products[$id];
    }

    /**
     * Load every product of the cart in one query, so the per-line lookups are
     * cache hits.
     *
     * @param  array<int, array<string, mixed>>  $lines
     */
    public function loadProducts(array $lines): void
    {
        $missing = collect($lines)
            ->pluck('product_id')
            ->filter(fn ($id) => $id !== null && ! array_key_exists((int) $id, $this->loadedProducts))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        if ($missing->isEmpty()) {
            return;
        }

        $found = Product::whereIn('id', $missing)->get()->keyBy('id');

        foreach ($missing as $id) {
            $this->products[$id] = $found->get($id);
            $this->loadedProducts[$id] = true;
        }
    }

    public function priceUnit(?int $id): ?PriceUnit
    {
        if ($id === null) {
            return null;
        }
        if (! array_key_exists($id, $this->priceUnits)) {
            $this->priceUnits[$id] = PriceUnit::find($id);
        }

        return $this->priceUnits[$id];
    }
}
