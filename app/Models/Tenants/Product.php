<?php

namespace App\Models\Tenants;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

use function Filament\Support\format_money;

/**
 * 
 *
 * @property int $id
 * @property int $category_id
 * @property string $name
 * @property string|null $sku
 * @property string|null $barcode
 * @property float $stock
 * @property int $is_non_stock
 * @property float $initial_price
 * @property float $selling_price
 * @property string $unit
 * @property string $type
 * @property string|null $hero_images
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tenants\CartItem> $CartItems
 * @property-read int|null $cart_items_count
 * @property-read \App\Models\Tenants\Category $category
 * @property mixed $selling_price_label
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tenants\Stock> $stocks
 * @property-read int|null $stocks_count
 * @method static \Database\Factories\Tenants\ProductFactory factory($count = null, $state = [])
 * @method static Builder|Product newModelQuery()
 * @method static Builder|Product newQuery()
 * @method static Builder|Product query()
 * @method static Builder|Product stockLatestIn()
 * @method static Builder|Product whereBarcode($value)
 * @method static Builder|Product whereCategoryId($value)
 * @method static Builder|Product whereCreatedAt($value)
 * @method static Builder|Product whereHeroImages($value)
 * @method static Builder|Product whereId($value)
 * @method static Builder|Product whereInitialPrice($value)
 * @method static Builder|Product whereIsNonStock($value)
 * @method static Builder|Product whereName($value)
 * @method static Builder|Product whereSellingPrice($value)
 * @method static Builder|Product whereSku($value)
 * @method static Builder|Product whereStock($value)
 * @method static Builder|Product whereType($value)
 * @method static Builder|Product whereUnit($value)
 * @method static Builder|Product whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Product extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'hero_images_url'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function CartItems(): HasMany
    {
        return $this->hasMany(CartItem::class)
            ->where('user_id', Filament::auth()->id());
    }

    public function scopeStockLatestIn()
    {
        $usingFifoPrice = Setting::get('selling_method', 'fifo') == 'fifo';
        $usingNormalPrice = Setting::get('selling_method', 'fifo') == 'normal';
        $usingLifoPrice = Setting::get('selling_method', 'fifo') == 'lifo';

        return $this
            ->stocks()
            ->where('type', 'in')
            ->when($usingNormalPrice, fn (Builder $query) => $query->orderBy('date')->latest())
            ->when($usingFifoPrice, fn (Builder $query) => $query
                ->where('stock', '>', 0)
                ->orderBy('created_at')->orderBy('date'))
            ->when($usingLifoPrice, fn (Builder $query) => $query
                ->where('stock', '>', 0)
                ->orderByDesc('created_at')->orderByDesc('date'));
    }

    public function stock(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $usingNormalPrice = Setting::get('selling_method', 'fifo') == 'normal';
                if ($usingNormalPrice) {
                    $lastStock = $this->stocks()->where('stock', '>', 0)->orderBy('date', 'asc')->first();

                    return ($lastStock ? $lastStock->stock : 0) + $value;
                }
                $stock = $this
                    ->stockLatestIn()
                    ->sum('stock');

                return $stock + $value;
            },
            set: fn ($value) => $value
        );
    }

    public function initialPrice(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $stock = $this
                    ->stockLatestIn();
                if ($stock?->first() == null) {
                    return $value;
                }

                return $stock->first()->initial_price;
            },
            set: fn ($value) => $value
        );
    }

    public function sellingPrice(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $stock = $this
                    ->stockLatestIn();
                if ($stock?->first() == null) {
                    return $value;
                }

                return $stock->first()->selling_price;
            },
            set: fn ($value) => $value
        );
    }

    public function sellingPriceLabel(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                return format_money($this->initial_price, Setting::get('currency', 'IDR'));
            },
            set: fn ($value) => $value
        );
    }

    public function heroImages(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Str::of($value)->explode(',') : [],
            set: fn ($value) => $value ? Arr::join(is_array($value) ? $value : $value->toArray(), ',') : null
        );
    }
}
