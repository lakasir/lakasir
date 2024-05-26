<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;

class RegisteredUserController extends Controller
{
    public function __invoke(RegisterRequest $request)
    {
        $tenant = $request->register();
        if ($tenant) {
            return response()->json([
                'success' => true,
                'message' => 'Success!',
                'data' => $tenant,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed!',
        ]);
    }
}
