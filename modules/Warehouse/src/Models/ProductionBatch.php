<?php

namespace Modules\Warehouse\Models;

use App\Models\Tenants\Stock;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionBatch extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function estimationSellingPrice(): Attribute
    {
        $totalCost = $this->total_cost + $this->added_cost;
        $sellingPrice = $totalCost + ($totalCost + $this->margin / 100);

        if ($this->complete_price) {
            $sellingPrice = ceil($sellingPrice / 500) * 500;
        }

        return Attribute::get(fn () => $sellingPrice);
    }

    public function grandTotalCost(): Attribute
    {
        return Attribute::get(fn () => $this->total_cost + $this->added_cost);
    }

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }
}
