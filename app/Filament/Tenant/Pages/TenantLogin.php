<?php

namespace App\Filament\Tenant\Pages;

use Filament\Facades\Filament;
use Filament\Forms\Components\Component;
use Filament\Http\Responses\Auth\LoginResponse;
use Filament\Pages\Auth\Login;
use Illuminate\Validation\ValidationException;

class TenantLogin extends Login
{
    public function authenticate(): ?LoginResponse
    {
        $loginResponse = parent::authenticate();
        /** @var \App\Models\Tenants\User $user */
        $user = Filament::auth()->user();
        if (!$user->can('access web app')) {
            throw ValidationException::withMessages([
                'data.email' => 'You do not have permission to access the web app',
            ]);

            return null;
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
                'password' => 'passwordsangatrahasia'
            ]);
        }
    }
}
