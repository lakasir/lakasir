<?php

namespace App\Models\Tenants;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use function Filament\Support\format_money;

/**
 * @mixin IdeHelperCartItem
 */
class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'qty',
        'price',
        'user_id',
        'product_id',
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
        return format_money($this->price, Setting::get('currency', 'IDR'));
    }

    public function getFinalPriceFormatAttribute()
    {
        return format_money($this->price - ($this->discount_price ?? 0), Setting::get('currency', 'IDR'));
    }

    public function getDiscountPriceFormatAttribute()
    {
        return format_money($this->discount_price, Setting::get('currency', 'IDR'));
    }

    public function heroImage(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->product?->hero_images ? $this->product->hero_images[0] : 'https://cdn4.iconfinder.com/data/icons/picture-sharing-sites/32/No_Image-1024.png';
            }
        );
    }
}
