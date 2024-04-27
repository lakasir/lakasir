<?php

namespace App\Policies\Tenants;

use App\Models\Tenants\User;
use App\Models\Tenants\User as ModelsUser;
use Filament\Facades\Filament;

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
        if ($member->is_owner) {
            return false;
        }

        return $user->can('update user');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ModelsUser $member): bool
    {
        if ($member->is_owner && ! Filament::auth()->user()->is_owner) {
            return false;
        }

        return $user->can('delete user');
    }
}
