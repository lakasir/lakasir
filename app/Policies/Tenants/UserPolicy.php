<?php

namespace App\Policies\Tenants;

use App\Models\Tenants\User;
use App\Models\Tenants\User as ModelsUser;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('read user');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ModelsUser $member): bool
    {
        return $user->can('read user');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create user');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ModelsUser $member): bool
    {
        return $user->can('update user');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ModelsUser $member): bool
    {
        return $user->can('delete user');
    }
}
