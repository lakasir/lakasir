<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $fillable = [
        'initial_price',
        'selling_price',
        'date'
    ];

    public function item()
    {
        return $this->belongsTo(Unit::class, 'item_id');
    }
}
