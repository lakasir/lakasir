<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DeleteUser
{
    use HandlesAuthorization;

    /**
     * undocumented function
     *
     * @return void
     */
    public function delete(User $user, User $userData): bool
    {
        $hasPurchasing = function(User $userData): bool
        {
            if ($userData->purchasings->count() > 0) {

                $message = __('app.user.message.error.delete_user.has_transaction');

                flash()->error(dash_to_space($message));

                return false;
            }
            return true;
        };
        if ($userData->isOwner) {

            $message = __('app.user.message.error.delete_user.owner');

            flash()->error(dash_to_space($message));

            return false;
        } else {
            return $hasPurchasing($userData);
        }
    }

}
