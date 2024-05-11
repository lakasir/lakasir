<?php

namespace App\Models\Tenants;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 
 *
 * @property int $id
 * @property int $opened_by
 * @property int|null $closed_by
 * @property float $cash
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Tenants\User|null $closedBy
 * @property-read \App\Models\Tenants\User $openedBy
 * @method static \Illuminate\Database\Eloquent\Builder|CashDrawer lastOpened()
 * @method static \Illuminate\Database\Eloquent\Builder|CashDrawer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CashDrawer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CashDrawer query()
 * @method static \Illuminate\Database\Eloquent\Builder|CashDrawer today()
 * @method static \Illuminate\Database\Eloquent\Builder|CashDrawer whereCash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashDrawer whereClosedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashDrawer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashDrawer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashDrawer whereOpenedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashDrawer whereUpdatedAt($value)
 * @mixin \Eloquent
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
