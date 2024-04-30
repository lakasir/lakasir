<?php

namespace App\Policies\Tenants;

use App\Models\Tenants\User;
use Spatie\Permission\Models\Permission;

class PermissionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('read permission');
    }

    public function view(User $user, Permission $permission): bool
    {
        return $user->can('read permission');
    }

    public function create(User $user): bool
    {
        return false;
    }
}
