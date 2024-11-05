<?php

namespace App\Models\Tenants;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Number;
use Illuminate\Support\Str;

use function Pest\Laravel\get;

/**
 * @mixin IdeHelperProduct
 */
class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id', 'hero_images_url', 'expired'];

    protected $appends = ['hero_image'];

    private int $expiredDay = 20;

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class)
            ->where('is_ready', 1);
    }

    public function CartItems(): HasMany
    {
        return $this->hasMany(CartItem::class)
            ->where('user_id', Filament::auth()->id());
    }

    public function scopeStockLatestCalculateIn()
    {
        $usingFifoPrice = Setting::get('selling_method', env('SELLING_METHOD', 'fifo')) == 'fifo';
        $usingNormalPrice = Setting::get('selling_method', env('SELLING_METHOD', 'fifo')) == 'normal';
        $usingLifoPrice = Setting::get('selling_method', env('SELLING_METHOD', 'fifo')) == 'lifo';

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

    public function stockCalculate(): Attribute
    {
        return Attribute::make(
            get: function () {
                // $usingNormalPrice = Setting::get('selling_method', env('SELLING_METHOD', 'fifo')) == 'normal';
                // if ($usingNormalPrice) {
                //     $lastStock = $this->stocks()->where('stock', '>', 0)
                //         ->orderBy('date', 'asc')
                //         ->first();
                //     dd($lastStock);
                //
                //     return $lastStock ? $lastStock->stock : 0;
                // }
                $stock = $this
                    ->stockLatestCalculateIn()
                    ->sum('stock');

                return $stock;
            },
            set: fn ($value) => $value
        );
    }

    public function initialPriceCalculate(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $stock = $this
                    ->stockLatestCalculateIn();
                if ($stock?->first() == null) {
                    return $value;
                }

                return $stock->first()->initial_price;
            },
            set: fn ($value) => $value
        );
    }

    public function sellingPriceCalculate(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $stock = $this
                    ->stockLatestCalculateIn();
                if ($stock?->first() == null) {
                    return $value;
                }

                return $stock->first()->selling_price;
            },
            set: fn ($value) => $value
        );
    }

    public function sellingPriceLabelCalculate(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                return Number::currency($this->initial_price, Setting::get('currency', 'IDR'));
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

    public function scopeNearestExpiredProduct(Builder $builder)
    {
        return $builder->whereHas('stocks', function (Builder $builder) {
            $nearestExpired = now()->addDay($this->expiredDay);

            return $builder
                ->whereDate('expired', '<=', $nearestExpired);
        });
    }

    public function expiredStock(): Attribute
    {
        return Attribute::make(
            get: function () {
                $nearestExpired = now()->addDay($this->expiredDay);

                return $this
                    ->stocks()
                    ->where('stock', '>', 0)
                    ->whereDate('expired', '<=', $nearestExpired)->latest()->first();
            }
        );
    }

    public function hasExpiredStock(): Attribute
    {
        return Attribute::make(
            get: function () {
                $nearestExpired = now()->addDay($this->expiredDay);

                return $this->stocks()
                    ->where('stock', '>', 0)
                    ->whereDate('expired', '<=', $nearestExpired)->exists();
            }
        );
    }

    public function setExpiredDay(int $day)
    {
        $this->expiredDay = $day;

        return $this;
    }

    public function heroImage(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->hero_images ? $this->hero_images[0] : 'https://cdn4.iconfinder.com/data/icons/picture-sharing-sites/32/No_Image-1024.png';
            }
        );
    }

    public function sellingDetails(): HasMany
    {
        return $this->hasMany(SellingDetail::class);
    }

    public function scopeInActivate(Builder $builder): Builder
    {
        return $builder->where('show', false);
    }

    public function priceUnits(): HasMany
    {
        return $this->hasMany(PriceUnit::class);
    }
}
