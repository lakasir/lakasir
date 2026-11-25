<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\PermissionRegistrar;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'domain',
        'enabled',
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_companies');
    }

    public function isCurrent(): bool
    {
        return static::getCurrent()?->id === $this->id;
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
