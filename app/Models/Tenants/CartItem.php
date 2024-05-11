<?php

namespace App\Models\Tenants;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use function Filament\Support\format_money;

/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property int $product_id
 * @property float $qty
 * @property float|null $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Tenants\User $cashier
 * @property-read mixed $price_format_m_oney
 * @property-read \App\Models\Tenants\Product $product
 * @method static Builder|CartItem cashier()
 * @method static Builder|CartItem newModelQuery()
 * @method static Builder|CartItem newQuery()
 * @method static Builder|CartItem query()
 * @method static Builder|CartItem whereCreatedAt($value)
 * @method static Builder|CartItem whereId($value)
 * @method static Builder|CartItem wherePrice($value)
 * @method static Builder|CartItem whereProductId($value)
 * @method static Builder|CartItem whereQty($value)
 * @method static Builder|CartItem whereUpdatedAt($value)
 * @method static Builder|CartItem whereUserId($value)
 * @mixin \Eloquent
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

    protected $appends = ['price_format_money'];

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
}
