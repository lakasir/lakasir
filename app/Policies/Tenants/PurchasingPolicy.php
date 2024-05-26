<?php

namespace App\Policies\Tenants;

use App\Models\Tenants\Purchasing;
use App\Models\Tenants\User;

class PurchasingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('read purchasing');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Purchasing $purchasing): bool
    {
        return $user->can('read purchasing');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create purchasing');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Purchasing $purchasing): bool
    {
        return $user->can('update purchasing');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Purchasing $purchasing): bool
    {
        return $user->can('delete purchasing');
    }
}
