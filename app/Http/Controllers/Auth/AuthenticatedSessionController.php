<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();
        /** @var \App\Models\Tenants\User $user */
        $user = $request->user();
        $token = $request->user()->createToken($user->getRememberTokenName());

        return response()->json([
            'success' => true,
            'message' => 'Yay! success to login',
            'data' => array_merge($user->toArray(), [
                'token' => $token->plainTextToken,
                'permissions' => $user->roles()->first()->permissions()->where('guard_name', 'sanctum')->pluck('name')->toArray(),
            ]),
        ]);
    }

    /**
     * Destroy an authenticated session.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }
}
