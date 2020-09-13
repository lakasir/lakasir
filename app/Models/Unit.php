<?php

namespace App\Models;

use App\DataTables\UnitTable;
use App\Traits\HasLaTable;
use Illuminate\Database\Eloquent\Model;
use Lakasir\UserLoggingActivity\Traits\HasLog;

class Unit extends Model
{
    use HasLaTable, HasLog;

    protected $fillable = ['name'];
}
