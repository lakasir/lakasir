<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasLaTable;

class Company extends Model
{
    use HasLaTable;

    protected $fillable = [
        'name',
        'reg_number',
        'business_type',
        'business_description',
        'address',
        'default_currency',
        'expected_max_employee'
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
