<?php

namespace App\Models\Tenants;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperStock
 */
class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock',
        'initial_price',
        'selling_price',
        'type',
        'date',
        'init_stock',
    ];

    protected $appends = [
        'total_selling_price',
        'total_initial_price',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function purchasing(): BelongsTo
    {
        return $this->belongsTo(Purchasing::class);
    }

    public function scopeProduct($query, $product_id)
    {
        return $query->where('product_id', $product_id);
    }

    public function scopeIn($query)
    {
        return $query->where('type', 'in');
    }

    public function scopeOut($query)
    {
        return $query->where('type', 'out');
    }

    public function scopeLatestIn()
    {
        return $this->where('type', 'in')
            ->where('stock', '>', 0)
            ->where('date', '<=', now());
    }

    public function totalSellingPrice(): Attribute
    {
        return Attribute::make(get: fn () => $this->selling_price * $this->stock);
    }

    public function totalInitialPrice(): Attribute
    {
        return Attribute::make(get: fn () => $this->initial_price * $this->stock);
    }
}
