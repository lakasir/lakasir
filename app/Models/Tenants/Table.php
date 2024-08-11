<?php

namespace App\Models\Tenants;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperTable
 */
class Table extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function Sellings(): HasMany
    {
        return $this->hasMany(Selling::class);
    }
}
