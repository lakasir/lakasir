<?php

namespace App\Models\Tenants;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperTable
 */
class Table extends Model
{
    use BelongsToCompany, HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function Sellings(): HasMany
    {
        return $this->hasMany(Selling::class);
    }
}
