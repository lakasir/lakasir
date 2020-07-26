<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name', 'email', 'code'];

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'customer_group', 'customer_id', 'group_id');
    }
}
