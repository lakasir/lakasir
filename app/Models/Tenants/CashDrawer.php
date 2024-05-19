<?php

namespace App\Models\Tenants;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperCashDrawer
 */
class CashDrawer extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function openedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'opened_by');
    }

    public function closedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function scopeToday()
    {
        return $this->whereDate('created_at', now());
    }

    public function scopeLastOpened()
    {
        return $this->whereNull('closed_by')->latest();
    }
}
