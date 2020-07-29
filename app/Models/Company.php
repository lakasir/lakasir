<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasLaTable;

class Company extends Model
{
    use HasLaTable;

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
