<?php

namespace App\Models;

use App\Traits\HasLaTable;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasLaTable;

    protected $fillable = ['name'];
}
