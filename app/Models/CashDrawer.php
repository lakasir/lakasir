<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashDrawer extends Model
{
    protected $fillable = ['money', 'today', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
