<?php

namespace App\Models\Tenants;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @mixin IdeHelperCartItem
 */
class CartItem extends Model
{
    use HasFactory;

    /**
     * The image a product without a photo shows. Inline, so it needs no route
     * and no third-party host: under a tenant the asset root points at
     * /tenancy/assets, which this app does not serve.
     */
    public const PLACEHOLDER_IMAGE = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 200 200'%3E%3Crect width='200' height='200' fill='%23F3F4F6'/%3E%3Cpath d='M40 140l35-45 25 32 20-25 40 38z' fill='%23D1D5DB'/%3E%3Ccircle cx='70' cy='70' r='14' fill='%23D1D5DB'/%3E%3C/svg%3E";

    protected $fillable = [
        'qty',
        'price',
        'user_id',
        'product_id',
        'price_unit_id',
    ];

    protected $appends = ['price_format_money', 'discount_price_format', 'final_price_format'];

    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeCashier(Builder $builder): Builder
    {
        return $builder->where('user_id', Filament::auth()->id());
    }

    public function getPriceFormatMOneyAttribute()
    {
        $priceUnit = $this->priceUnit?->selling_price;
        if ($priceUnit) {
            $priceUnit = $priceUnit * $this->qty;
        }

        return price_format($priceUnit ?? $this->price);
    }

    public function getFinalPriceFormatAttribute()
    {
        return price_format($this->price - ($this->discount_price ?? 0));
    }

    public function getDiscountPriceFormatAttribute()
    {
        return price_format($this->discount_price);
    }

    public function heroImage(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->product?->hero_images && count($this->product->hero_images) > 0) {
                    $path = $this->product->hero_images[0];
                    if (Str::startsWith($path, ['http://', 'https://'])) {
                        return $path;
                    }
                    $uploadDisk = config('filesystems.upload_disk');
                    return Storage::disk($uploadDisk)->url($path);
                }
                // Inline pixels: under a tenant the asset root points at
                // /tenancy/assets, which this app does not serve, and a
                // third-party host fails on a shop with no internet.
                return self::PLACEHOLDER_IMAGE;
            }
        );
    }

    public function priceUnit(): BelongsTo
    {
        return $this->belongsTo(PriceUnit::class);
    }
}