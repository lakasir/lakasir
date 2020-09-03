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
        'visible_in',
        'can_delete'
    ];

    public function getArrayVisibleInAttribute()
    {
        return json_decode($this->getAttribute('visible_in'), 1);
    }
}
