<?php

namespace App\Models\Tenants;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperPrinter
 */
class Printer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'driver',
        'port',
        'ip_address',
        'logo',
        'footer_text',
    ];
}
