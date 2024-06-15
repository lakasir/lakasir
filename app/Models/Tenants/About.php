<?php

namespace App\Models\Tenants;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperAbout
 */
class About extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
}
