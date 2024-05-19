<?php

namespace App\Models\Tenants;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

use function Filament\Support\format_money;

/**
 * @mixin IdeHelperProduct
 */
class Product extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'hero_images_url'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function stocks(): HasMany
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

    public function netProfit(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->selling_price - $this->initial_price
        );
    }
}
