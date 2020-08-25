<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Selling extends Model
{
    protected $fillable = [
        'number_transaction',
        'transaction_date',
        'total_price',
        'total_qty',
        'total_profit',
        'money',
        'refund',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function sellingDetail()
    {
        return $this->hasMany(SellingDetail::class);
    }
}
