<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = [
        'current_stock',
        'last_stock',
        'date'
    ];

    public function item()
    {
        return $this->belongsTo(Unit::class, 'item_id');
    }
}
