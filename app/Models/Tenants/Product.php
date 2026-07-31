<?php

namespace App\Models\Tenants;

use App\Models\Tenants\Traits\HasProductExpiration;
use App\Models\Tenants\Traits\HasProductMedia;
use App\Models\Tenants\Traits\HasProductPricing;
use App\Models\Tenants\Traits\HasProductStock;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperProduct
 */
class Product extends Model
{
    use HasFactory, SoftDeletes;
    use HasProductStock, HasProductPricing, HasProductExpiration, HasProductMedia;

    protected $guarded = ['id', 'hero_images_url', 'expired'];

    protected $appends = ['hero_image'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function CartItems(): HasMany
    {
        return $this->hasMany(CartItem::class)
            ->where('user_id', Filament::auth()->id());
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
