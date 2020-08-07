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
}
