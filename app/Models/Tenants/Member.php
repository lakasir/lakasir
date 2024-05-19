<?php

namespace App\Models\Tenants;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperMember
 */
class Member extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
}
