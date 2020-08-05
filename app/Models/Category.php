<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasLaTable;

class Category extends Model
{
    use HasLaTable;

    protected $fillable = ['name'];
}
