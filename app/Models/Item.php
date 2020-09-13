<?php

namespace App\Models;

use App\Traits\Media;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasLaTable;

class Item extends Model
{
    use Media;
    use HasLaTable;

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

    public function log_stocks()
    {
        return $this->hasMany(Stock::class, 'item_id')->orderBy('date', 'asc');
    }

    public function getLastStockAttribute()
    {
        return Stock::where('item_id', $this->id)->orderBy('date', 'asc')->first();
    }

    public function getLastPriceAttribute()
    {
        return optional($this->last_stock)->price;
    }
}
