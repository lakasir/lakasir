<?php

namespace App\Filament\Tenant\Pages;

use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\LoginResponse;
use Filament\Pages\Auth\Login;
use Illuminate\Validation\ValidationException;

class TenantLogin extends Login
{
    public function authenticate(): ?LoginResponse
    {
        $loginResponse = parent::authenticate();
        /** @var \App\Models\User $user */
        $user = Filament::auth()->user();
        if (! $user->can('access web app')) {
            throw ValidationException::withMessages([
                'data.email' => 'You do not have permission to access the web app',
            ]);

            return null;
        }

        return $loginResponse;
    }
}
