<?php

namespace App\Models\Tenants;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $driver
 * @property string|null $port
 * @property string|null $ip_address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Printer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Printer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Printer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Printer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Printer whereDriver($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Printer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Printer whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Printer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Printer wherePort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Printer whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Printer extends Model
{
    use HasFactory;

    protected $guarded = [];
}
