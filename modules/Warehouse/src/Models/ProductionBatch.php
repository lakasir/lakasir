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

    /**
     * HPP for one produced unit: the whole batch cost spread over the batch.
     */
    public function hppPerUnit(): Attribute
    {
        return Attribute::get(fn () => $this->grand_total_cost / max(1, (int) $this->quantity_produced));
    }

    public function estimationSellingPrice(): Attribute
    {
        return Attribute::get(function () {
            $sellingPrice = $this->hpp_per_unit * (1 + $this->margin / 100);

            if ($this->complete_price) {
                $sellingPrice = ceil($sellingPrice / 500) * 500;
            }

            return $sellingPrice;
        });
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
