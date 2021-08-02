<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasLaTable;

class Supplier extends Model
{
    use HasLaTable;

    protected $fillable = [
        'name',
        'shop_name',
        'email',
        'phone',
        'address',
        'code'
    ];

    public function purchasings()
    {
        return $this->hasMany(Purchasing::class, 'supplier_id');
    }
}
