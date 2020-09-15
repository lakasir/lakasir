<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellingDetail extends Model
{
    protected $fillable = [
        'qty',
        'price',
        'profit',
    ];

    public function selling()
    {
        return $this->belongsTo(Selling::class, 'selling_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
