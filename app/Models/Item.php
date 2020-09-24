<?php

namespace App\Models;

use App\DataTables\ItemTable;
use App\Traits\Media;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasLaTable;
use Illuminate\Support\Facades\DB;

class Item extends Model
{
    use Media;
    use HasLaTable;

    protected $latable = ItemTable::class;

    protected $fillable = [
        'name',
        'internal_production',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function sellingDetails()
    {
        return $this->hasMany(SellingDetail::class);
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

    public function getStockAttribute()
    {
        return $this->log_stocks()->sum('amount');
    }


    public function getLastStockAttribute()
    {
        return Stock::where('amount', '>', 0)->where('item_id', $this->id)->orderBy('date', 'asc')->first();
    }

    public function getLastPriceAttribute()
    {
        $stockPrice = optional($this->last_stock)->price;
        if (!$stockPrice) {
            if ($this->prices->last()) {
                return $this->prices->last();
            } else {
                return (object) [
                    'initial_price' => 0,
                    'selling_price' => 0
                ];
            }
        }

        return $stockPrice;
    }

}
