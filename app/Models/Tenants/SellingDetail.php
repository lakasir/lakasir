<?php

namespace App\Models\Tenants;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperSellingDetail
 */
class SellingDetail extends Model
{
    use BelongsToCompany, HasFactory;

    protected $guarded = ['id'];

    public function selling(): BelongsTo
    {
        return $this->belongsTo(Selling::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function pricePerUnit(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->price / $this->qty,
        );
    }

    public function totalPrice(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->price - $this->discount_price,
        );
    }
}
