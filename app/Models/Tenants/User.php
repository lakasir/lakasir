<?php

namespace App\Models\Tenants;

use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasName;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * 
 *
 * @property int $id
 * @property string|null $fcm_token
 * @property int $is_owner
 * @property string|null $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Tenants\CashDrawer|null $cashDrawer
 * @property-read mixed $cashier_name
 * @property-read mixed $full_name
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \App\Models\Tenants\Profile|null $profile
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \App\Models\Tenants\SecureInitialPrice|null $secureInitialPrice
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tenants\Selling> $sellings
 * @property-read int|null $sellings_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read User|null $user
 * @method static \Database\Factories\Tenants\UserFactory factory($count = null, $state = [])
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User owner()
 * @method static Builder|User permission($permissions)
 * @method static Builder|User query()
 * @method static Builder|User role($roles, $guard = null)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereFcmToken($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereIsOwner($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable implements HasAvatar, HasName
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

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
        'is_owner',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
        return $this->profile?->photo ?? null;
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

    public function scopeOwner(Builder $builder)
    {
        return $builder->whereIsOwner(true);
    }
}
