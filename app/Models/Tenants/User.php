<?php

namespace App\Models\Tenants;

use App\Models\Company;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * @mixin IdeHelperUser
 */
class User extends Authenticatable implements FilamentUser, HasAvatar, HasName
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'fcm_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'fcm_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'user_companies')
            ->withPivot('is_owner');
    }

    /**
     * Ownership is per membership, never per person: in one box serving many
     * customers a login can belong to several shops, and an owner of one must
     * not be an owner of the others.
     */
    public function isOwner(?int $companyId = null): bool
    {
        $companyId ??= Company::currentId();

        if ($companyId === null) {
            return false;
        }

        return (bool) $this->companies()
            ->whereKey($companyId)
            ->wherePivot('is_owner', true)
            ->exists();
    }

    public function ownsAnyCompany(): bool
    {
        return (bool) $this->companies()->wherePivot('is_owner', true)->exists();
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function secureInitialPrice(): HasOne
    {
        return $this->hasOne(SecureInitialPrice::class);
    }

    public function cashDrawer(): HasOne
    {
        return $this->hasOne(CashDrawer::class);
    }

    public function sellings(): HasMany
    {
        return $this->hasMany(Selling::class);
    }

    public function getFilamentName(): string
    {
        return $this->name ?? '';
    }

    public function getFullNameAttribute()
    {
        return $this->name ?? '';
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->profile?->photo ? Storage::disk(config('filesystems.default'))->url($this->profile->photo) : null;
    }

    public function cashierName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->name ?? $this->email
        );
    }

    public function routeNotificationForFcm()
    {
        return $this->fcm_token;
    }

    /**
     * The owner of the shop in context. Ownership is per membership, so this
     * looks at the pivot for the current company instead of a users column.
     */
    public function scopeOwner(Builder $builder)
    {
        $companyId = Company::currentId();

        if ($companyId === null) {
            return $builder->whereRaw('1 = 0');
        }

        return $builder->whereHas(
            'companies',
            fn ($q) => $q->where('companies.id', $companyId)
                ->where('user_companies.is_owner', true),
        );
    }
}
