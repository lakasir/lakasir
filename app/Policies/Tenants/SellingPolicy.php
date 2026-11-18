<?php

namespace App\Policies\Tenants;

use App\Models\Tenants\Selling;
use App\Models\Tenants\User;

class SellingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('read selling');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Selling $selling): bool
    {
        return $user->can('read selling');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create selling');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Selling $selling): bool
    {
        return $user->can('update selling');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Selling $selling): bool
    {
        return $user->can('delete selling');
    }
}
