<?php

namespace App\Policies\Tenants;

use App\Models\Tenants\Debt;
use App\Models\Tenants\User;

class DebtPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('read debt');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Debt $debt): bool
    {
        return $user->can('read debt');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Debt $debt): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Debt $debt): bool
    {
        return false;
    }
}
