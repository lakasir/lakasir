<?php

namespace App\Models;

use App\Models\Tenants\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\PermissionRegistrar;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'domain',
        'enabled',
        'trial_ends_at',
        'provisioned_by',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'trial_ends_at' => 'datetime',
    ];

    /**
     * A hosted shop runs on a trial until it subscribes. A local one has no
     * trial date at all, which is what makes self-hosting free forever.
     */
    public function onTrial(): bool
    {
        return $this->trial_ends_at !== null && $this->trial_ends_at->isFuture();
    }

    public function trialExpired(): bool
    {
        return $this->trial_ends_at !== null && $this->trial_ends_at->isPast();
    }

    public function trialDaysLeft(): ?int
    {
        if (! $this->onTrial()) {
            return null;
        }

        // Carbon 3 returns a float, and a trial set to "30 days from now" is
        // 29.9999 days away, which truncates to 29. Round up.
        return (int) ceil(now()->diffInDays($this->trial_ends_at, true));
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_companies');
    }

    public function isCurrent(): bool
    {
        return static::getCurrent()?->id === $this->id;
    }

    public function scopeHasUser(Builder $builder): Builder
    {
        return $builder->whereHas('users', fn(Builder $builder) => $builder->where('user_id', Auth::id()));
    }

    public function makeCurrent(): static
    {
        app()->instance(static::class, $this);

        // Spatie's teams feature reads the team id from the registrar, and the
        // permission cache is keyed by it, so both follow the current company.
        $registrar = app(PermissionRegistrar::class);
        $registrar->setPermissionsTeamId($this->id);
        $registrar->forgetCachedPermissions();

        return $this;
    }

    public static function getCurrent(): ?static
    {
        return app()->bound(static::class) ? app(static::class) : null;
    }

    public static function forgetCurrent(): void
    {
        app()->forgetInstance(static::class);
    }

    public static function currentId(): ?int
    {
        return static::getCurrent()?->id;
    }
}
