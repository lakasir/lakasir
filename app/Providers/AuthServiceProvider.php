<?php

namespace App\Providers;

use App\Tenant;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    public function register() {
        Sanctum::ignoreMigrations();
    }

    public function boot()
    {
        $this->registerPolicies();

        ResetPassword::createUrlUsing(function ($notifiable, $token) {
            /** @var Tenant $tenant */
            $tenant = Tenant::whereHas('user', fn ($q) => $q->where('email', $notifiable->getEmailForPasswordReset()))->first();
            $domaaain = $tenant->domains()->first()->domain;

            return "https://$domaaain/reset-password/$token?email=" . urlencode($notifiable->getEmailForPasswordReset());
        });
    }
}
