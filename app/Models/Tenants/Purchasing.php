<?php

namespace App\Models\Tenants;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * @mixin IdeHelperPurchasing
 */
class Purchasing extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    // public function status(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn ($value) => Str::of($value)->title(),
    //     );
    // }
}
