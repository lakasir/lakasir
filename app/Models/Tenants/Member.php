<?php

namespace App\Models\Tenants;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperMember
 */
class Member extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function receivables(): HasMany
    {
        return $this->hasMany(Receivable::class);
    }
}
