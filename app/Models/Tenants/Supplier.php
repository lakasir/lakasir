<?php

namespace App\Models\Tenants;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperSupplier
 */
class Supplier extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
}
