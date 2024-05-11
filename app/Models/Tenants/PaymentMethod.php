<?php

namespace App\Models\Tenants;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property int $is_cash
 * @property int $is_debit
 * @property int $is_credit
 * @property int $is_wallet
 * @property-read string|null $icon
 * @property string|null $waletable_type
 * @property int|null $waletable_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod query()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereIsCash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereIsCredit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereIsDebit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereIsWallet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereWaletableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereWaletableType($value)
 * @mixin \Eloquent
 */
class PaymentMethod extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function icon(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => config('app.url') . '/' . $value,
        );
    }
}
