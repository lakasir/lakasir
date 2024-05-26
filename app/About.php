<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string|null $shop_name
 * @property string|null $shop_location
 * @property string $currency
 * @property string|null $business_type
 * @property string|null $other_business_type
 * @property string|null $photo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|About newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|About newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|About query()
 * @method static \Illuminate\Database\Eloquent\Builder|About whereBusinessType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|About whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|About whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|About whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|About whereOtherBusinessType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|About wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|About whereShopLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|About whereShopName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|About whereUpdatedAt($value)
 * @mixin \Eloquent
 * @mixin IdeHelperAbout
 */
class About extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_name',
        'shop_location',
        'business_type',
        'tenant_user_id',
        'photo',
    ];
}
