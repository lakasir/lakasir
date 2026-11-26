<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tenants\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     *
     * @return RedirectResponse
     */
    public function __invoke(EmailVerificationRequest $request)
    {
        $user = User::find($request->route('id'));
        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(
                config('app.frontend_url').'/auth/login'
            );
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended(config('app.frontend_url').'/auth/login');
    }
}
