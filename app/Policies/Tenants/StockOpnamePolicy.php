<?php

namespace App\Policies\Tenants;

use App\Models\Tenants\StockOpname;
use App\Models\Tenants\User;

class StockOpnamePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('read stock opname');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, StockOpname $purchasing): bool
    {
        return $user->can('read stock opname');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create stock opname');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, StockOpname $purchasing): bool
    {
        return $user->can('update stock opname');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, StockOpname $purchasing): bool
    {
        return $user->can('delete stock opname');
    }
}
