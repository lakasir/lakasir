<?php

namespace App\Services;

use App\Repositories\User;
use Illuminate\Http\Request;

/**
 * Service For Complect Logic which related with User
 */
class UserService
{
    public function index(Request $request)
    {
        return (new User)->getModel()::with('roles')->latest()->get();
    }

    public function updatePassword(Request $request)
    {
        $request->merge([
            'password' => bcrypt($request->new_password),
            'username' => auth()->user()->username,
            'email' => auth()->user()->email,
        ]);

        return (new User)->updatePassword($request, auth()->user());
    }
}
