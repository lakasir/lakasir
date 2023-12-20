<?php

namespace App\Models\Tenants;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Selling extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function sellingDetails()
    {
        return $this->hasMany(SellingDetail::class);
    }
}
