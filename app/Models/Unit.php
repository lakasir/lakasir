<?php

namespace App\Models;

use App\DataTables\UnitTable;
use App\Traits\HasLaTable;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasLaTable;

    protected $fillable = ['name'];

    protected $latable = UnitTable::class;
}
