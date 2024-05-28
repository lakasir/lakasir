<?php

namespace App\Policies\Tenants;

use App\Models\Tenants\User;
use App\Models\Tenants\Voucher;

class VoucherPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('read voucher');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Voucher $voucher): bool
    {
        return $user->can('read voucher');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create voucher');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Voucher $voucher): bool
    {
        return $user->can('update voucher');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Voucher $voucher): bool
    {
        return $user->can('delete voucher');
    }
}
