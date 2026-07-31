<?php

namespace App\Filament\Tenant\Pages;

use Filament\Facades\Filament;
use Filament\Forms\Components\Component;
use Filament\Http\Responses\Auth\LoginResponse;
use Filament\Pages\Auth\Login;
use Illuminate\Validation\ValidationException;

class TenantLogin extends Login
{
    protected static string $view = 'filament.pages.auth.login';
    protected static string $layout = 'filament-panels::components.layout.base';

    public function authenticate(): ?LoginResponse
    {
        if (app()->environment('local')) {
            $user = \App\Models\Tenants\User::first();
            Filament::auth()->login($user);
            session()->regenerate();
            $loginResponse = app(LoginResponse::class);
        } else {
            try {
                $loginResponse = parent::authenticate();
            } catch (ValidationException $e) {
                \Log::error('Login Validation Failed: ' . json_encode($e->errors()));
                throw $e;
            } catch (\Exception $e) {
                \Log::error('Login Exception: ' . $e->getMessage());
                throw $e;
            }
        }

        /** @var \App\Models\Tenants\User $user */
        $user = Filament::auth()->user();
        if (!$user->can('access web app')) {
            \Log::error('User lacks access web app permission.');
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
        } elseif (app()->environment('local')) {
            $this->form->fill([
                'email' => 'superadmin@admin.com',
                'password' => 'password'
            ]);
        }
    }
}
