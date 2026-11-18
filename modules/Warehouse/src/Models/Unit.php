<?php

namespace Modules\Warehouse\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'factor' => 'float',
    ];
}
