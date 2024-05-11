<?php

namespace App\Models\Tenants;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 
 *
 * @property int $id
 * @property int $product_id
 * @property float $stock
 * @property float $init_stock
 * @property float|null $initial_price
 * @property float|null $selling_price
 * @property string $type
 * @property string $date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Tenants\Product $product
 * @method static \Database\Factories\Tenants\StockFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Stock in()
 * @method static \Illuminate\Database\Eloquent\Builder|Stock latestIn()
 * @method static \Illuminate\Database\Eloquent\Builder|Stock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Stock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Stock out()
 * @method static \Illuminate\Database\Eloquent\Builder|Stock product($product_id)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock query()
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereInitStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereInitialPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereSellingPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereUpdatedAt($value)
 * @mixin \Eloquent
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

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
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
}
