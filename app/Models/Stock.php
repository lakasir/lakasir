<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = [
        'amount',
        'date'
    ];

    public function item()
    {
        return $this->belongsTo(Unit::class, 'item_id');
    }

    public function price()
    {
        return $this->belongsTo(Price::class, 'price_id');
    }

}
