<?php

namespace App\Http\Controllers\Api\Tenants;

use App\Http\Controllers\Controller;
use App\Models\Tenants\User;
use Illuminate\Http\Request;

class RegisterFCMTokenController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = User::find(auth()->id());
        $user->fill($request->all());
        $user->save();

        return $this->success([]);
    }
}
