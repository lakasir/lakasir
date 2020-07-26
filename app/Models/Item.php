<?php

namespace App\Models;

use App\Traits\Media;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use Media;

    protected $fillable = [
        'name',
        'internal_production',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function prices()
    {
        return $this->hasMany(Price::class, 'item_id');
    }
}
