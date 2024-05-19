<?php

namespace App\Policies\Tenants;

use App\Models\Tenants\DebtPayment;
use App\Models\Tenants\User;

class DebtPaymentPolicy
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
    public function view(User $user, DebtPayment $debtPayment): bool
    {
        return $user->can('read debt payment');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create debt payment');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DebtPayment $debtPayment): bool
    {
        return $user->can('update debt payment');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DebtPayment $debtPayment): bool
    {
        return $user->can('delete debt payment');
    }
}
