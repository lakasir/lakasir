<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerPoint extends Model
{
    protected $fillable = ['point', 'date'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
