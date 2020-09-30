<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasLaTable;

class Customer extends Model
{
    use HasLaTable;

    protected $fillable = ['name', 'email', 'code'];

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'customer_group', 'customer_id', 'group_id');
    }

    public function points()
    {
        return $this->hasMany(CustomerPoint::class, 'customer_id');
    }

    public function customerType()
    {
        return $this->belongsTo(CustomerType::class, 'customer_type_id');
    }

}
