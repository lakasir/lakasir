<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchasingDetail extends Model
{
    protected $fillable = [
        'qty',
        'initial_price',
        'selling_price',
        'item_id',
        'purchasing_id'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function purchasing()
    {
        return $this->belongsTo(Purchasing::class, 'purchasing_id');
    }

    public function getRowTotalAttribute()
    {
        return price_format($this->initial_price * $this->qty);
    }
}
