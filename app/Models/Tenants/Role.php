<?php

namespace App\Models\Tenants;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Models\Role as ModelsRole;
use Spatie\Permission\PermissionRegistrar;

/**
 * @mixin IdeHelperRole
 */
class Role extends ModelsRole
{
    public function mobilePermissions(): BelongsToMany
    {
        return $this->belongsToMany(
            config('permission.models.permission'),
            config('permission.table_names.role_has_permissions'),
            app(PermissionRegistrar::class)->pivotRole,
            app(PermissionRegistrar::class)->pivotPermission
        )->where('guard_name', 'sanctum');
    }

    public function webPermissions(): BelongsToMany
    {
        return $this->belongsToMany(
            config('permission.models.permission'),
            config('permission.table_names.role_has_permissions'),
            app(PermissionRegistrar::class)->pivotRole,
            app(PermissionRegistrar::class)->pivotPermission
        )->where('guard_name', 'web');
    }
}
