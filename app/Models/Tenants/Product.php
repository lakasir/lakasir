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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Number;
use Illuminate\Support\Str;

/**
 * @mixin IdeHelperProduct
 *
 * @property string $hero_images The comma-separated relative paths stored in DB (raw column)
 * @property array $heroImages The array of relative paths (via accessor; same as $hero_images)
 * @property string $heroImage The URL of the first hero image (accessor)
 * @property array $heroImagesUrl The array of full URLs (accessor)
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
            ->when($usingNormalPrice, fn(Builder $query) => $query->orderBy('date')->latest())
            ->when($usingFifoPrice, fn(Builder $query) => $query
                ->where('stock', '>', 0)
                ->orderBy('created_at')->orderBy('date'))
            ->when($usingLifoPrice, fn(Builder $query) => $query
                ->where('stock', '>', 0)
                ->orderByDesc('created_at')->orderByDesc('date'));
    }

    public function stockCalculate(): Attribute
    {
        return Attribute::make(
            get: function () {
                $stock = $this
                    ->stockLatestCalculateIn()
                    ->sum('stock');

                return $stock;
            },
            set: fn($value) => $value
        );
    }

    public function initialPriceCalculate(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $stock = $this
                    ->stockLatestCalculateIn();
                if ($stock?->first() == null) {
                    return $this->initial_price ?? 0;
                }

                return $stock->first()->initial_price;
            },
            set: fn($value) => $value
        );
    }

    public function sellingPriceCalculate(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $stock = $this
                    ->stockLatestCalculateIn();
                if ($stock?->first() == null) {
                    return $this->selling_price ?? 0;
                }

                return $stock->first()->selling_price;
            },
            set: fn($value) => $value
        );
    }

    public function sellingPriceLabelCalculate(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                return Number::currency($this->initial_price, Setting::get('currency', 'IDR'));
            },
            set: fn($value) => $value
        );
    }

    /**
     * Accessor for hero_images: returns an array of relative paths.
     * The DB column stores comma-separated relative paths.
     */
    public function heroImages(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? Str::of($value)->explode(',')->toArray() : [],
            set: fn($value) => $value ? Arr::join(is_array($value) ? $value : $value->toArray(), ',') : null
        );
    }

    /**
     * Accessor for hero_images_url: returns an array of full URLs computed from relative paths.
     * Passes through absolute URLs unchanged for backward compatibility with legacy data.
     */
    public function heroImagesUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                $uploadDisk = config('filesystems.upload_disk');
                return Arr::map($this->hero_images, function ($path) use ($uploadDisk) {
                    if (Str::startsWith($path, ['http://', 'https://'])) {
                        return $path;
                    }
                    return Storage::disk($uploadDisk)->url($path);
                });
            }
        );
    }

    public function netProfit(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->selling_price - $this->initial_price
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

    /**
     * Accessor for heroImage: returns the full URL of the first hero image.
     * Passes through absolute URLs unchanged for backward compatibility with legacy data.
     */
    public function heroImage(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->hero_images && count($this->hero_images) > 0) {
                    $path = $this->hero_images[0];
                    if (Str::startsWith($path, ['http://', 'https://'])) {
                        return $path;
                    }
                    $uploadDisk = config('filesystems.upload_disk');
                    return Storage::disk($uploadDisk)->url($path);
                }
                return 'https://cdn4.iconfinder.com/data/icons/picture-sharing-sites/32/No_Image-1024.png';
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

    public function scopeInStock(Builder $builder): Builder
    {
        return $builder->where(function ($query) {
            $query->whereHas('stocks', function ($query) {
                $query->where('is_ready', 1)
                    ->where('type', 'in')
                    ->where('stock', '>', 0);
            })
            ->orWhere('is_non_stock', true);
        });
    }

    /**
     * Get all barcodes associated with the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function barcodes(): HasMany
    {
        return $this->hasMany(Barcode::class);
    }


    /**
     * Get the primary and active barcode(s) for the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function primaryBarcode(): HasMany
    {
        return $this->hasMany(Barcode::class)->where('type', 'primary')->where('is_active', true);
    }

    /**
     * Find a product by searching for its barcode or SKU.
     *
     * It first attempts to find the product via an active barcode,
     * then falls back to searching by the product's SKU.
     *
     * @param string $code The barcode or SKU string to search for.
     * @return ?Product The product model, or null if not found.
     */
    public static function findByBarcodeOrSku(string $code): ?Product
    {
        $product = Barcode::findProductByCode($code);

        if ($product) {
            return $product;
        }
        return static::where('sku', $code)->first();
    }
}