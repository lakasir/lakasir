<?php

namespace App\Models\Tenants;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock',
        'initial_price',
        'selling_price',
        'type',
        'date'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeProduct($query, $product_id)
    {
        return $query->where('product_id', $product_id);
    }

    public function scopeIn($query)
    {
        return $query->where('type', 'in');
    }

    public function scopeOut($query)
    {
        return $query->where('type', 'out');
    }

    public function getInitialPriceAttribute($value)
    {
        if ($this->stock == 0) {
            return 0;
        } else {
            return $value;
        }
    }
}
