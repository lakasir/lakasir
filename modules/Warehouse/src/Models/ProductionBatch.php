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

    protected $casts = [
        'quantity_produced' => 'integer',
        'total_cost' => 'float',
        'added_cost' => 'float',
        'margin' => 'float',
        'complete_price' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (ProductionBatch $batch) {
            if (blank($batch->code)) {
                $batch->code = self::nextCode();
            }
        });
    }

    /**
     * Batch codes follow BATCH-0001 style, continuing from the highest
     * existing one so deleted batches do not cause collisions.
     */
    public static function nextCode(): string
    {
        $last = self::query()
            ->where('code', 'like', 'BATCH-%')
            ->orderByRaw('CAST(SUBSTRING(code, 7) AS UNSIGNED) DESC')
            ->value('code');

        $next = $last ? ((int) substr($last, 6)) + 1 : 1;

        return 'BATCH-'.str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }

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
