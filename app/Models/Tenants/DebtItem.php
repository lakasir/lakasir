<?php

namespace App\Models\Tenants;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperDebtItem
 */
class DebtItem extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function debt(): BelongsTo
    {
        return $this->belongsTo(Debt::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
