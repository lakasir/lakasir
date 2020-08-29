<?php

namespace App\Models;

use App\DataTables\PaymentMethodTable;
use App\Traits\HasLaTable;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasLaTable;

    protected $latable = PaymentMethodTable::class;

    protected $fillable = [
        'name',
        'code',
        'visible_in'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            $query->can_delete = true;
        });
    }

    public function getArrayVisibleInAttribute()
    {
        return json_decode($this->getAttribute('visible_in'), 1);
    }
}
