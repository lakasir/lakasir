<?php

namespace Modules\Warehouse\Models;

use App\Models\Tenants\Product as TenantsProduct;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends TenantsProduct
{
    use HasFactory;

    public function productionRecipeItems(): HasMany
    {
        return $this->hasMany(ProductionRecipeItem::class);
    }

    public function productionBatches(): HasMany
    {
        return $this->hasMany(ProductionBatch::class);
    }
}
