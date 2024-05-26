<?php

namespace App\Models\Tenants;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
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
