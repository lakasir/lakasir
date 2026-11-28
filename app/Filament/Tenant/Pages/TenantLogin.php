<?php

namespace App\Filament\Tenant\Pages;

use App\Models\Tenants\User;
use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\LoginResponse;
use Filament\Pages\Auth\Login;
use Illuminate\Validation\ValidationException;

class TenantLogin extends Login
{
    public function authenticate(): ?LoginResponse
    {
        $loginResponse = parent::authenticate();
        /** @var User $user */
        $user = Filament::auth()->user();
        $user->resolveCompany()?->makeCurrent();
        if (! $user->can('access web app')) {
            throw ValidationException::withMessages([
                'data.email' => 'You do not have permission to access the web app',
            ]);
        }
        $user->profile()->updateOrCreate(
            [
                'user_id' => $user->getKey(),
            ],
            [
                'timezone' => 'Asia/Jakarta',
            ]
        );

        return $loginResponse;
    }

    public function mount(): void
    {
        if (Filament::auth()->check()) {
            redirect()->intended(Filament::getUrl());
        }

        if (app()->environment('demo')) {
            $this->form->fill([
                'email' => 'demo@lakasir.com',
                'password' => 'passwordsangatrahasia',
            ]);
        }
    }
}
