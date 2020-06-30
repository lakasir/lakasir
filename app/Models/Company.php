<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'reg_number',
        'business_type',
        'business_description'
    ];

    /**
     * user relation
     *
     * @return $this
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
