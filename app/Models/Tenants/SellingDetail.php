<?php

namespace App\Models\Tenants;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
