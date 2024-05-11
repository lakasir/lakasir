<?php

namespace App\Models\Tenants;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $selling_id
 * @property int $product_id
 * @property float $price
 * @property float|null $cost
 * @property float $qty
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Tenants\Product $product
 * @property-read \App\Models\Tenants\Selling $selling
 * @method static \Database\Factories\Tenants\SellingDetailFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|SellingDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SellingDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SellingDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder|SellingDetail whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellingDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellingDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellingDetail wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellingDetail whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellingDetail whereQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellingDetail whereSellingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellingDetail whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SellingDetail extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function selling()
    {
        return $this->belongsTo(Selling::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
