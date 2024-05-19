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

    protected $fillable = [
        'shop_name',
        'shop_location',
        'business_type',
        'other_business_type',
        'photo',
    ];
}
