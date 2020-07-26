<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchasing extends Model
{
    protected $fillable = [
        'date',
        'payment_method',
        'invoice_number',
        'total_initial_price',
        'total_selling_price',
        'total_qty',
        'note',
        'is_paid'
    ];

    public function purchasingDetails()
    {
        return $this->hasMany(PurchasingDetail::class, 'purchasing_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
}
