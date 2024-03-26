<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\Tenants\Category;
use App\Models\Tenants\Member;
use App\Models\Tenants\Product;
use App\Models\Tenants\User;
use App\Policies\Tenants\CategoryPolicy;
use App\Policies\Tenants\MemberPolicy;
use App\Policies\Tenants\ProductPolicy;
use App\Tenant;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Laravel\Sanctum\Sanctum;

class AuthServiceProvider extends ServiceProvider
{
    private $defaultAbility = [
        'viewAny',
        'view',
        'create',
        'update',
        'delete',
        'restore',
        'forceDelete',
    ];

    protected $policies = [
        Member::class => MemberPolicy::class,
        Product::class => ProductPolicy::class,
        Category::class => CategoryPolicy::class,
    ];

    public function register()
    {
        Sanctum::ignoreMigrations();
    }

    public function boot()
    {
        $this->registerPolicies();

        Gate::after(function (User|Admin $user, $ability) {
            if (in_array($ability, $this->defaultAbility)) {
                return true;
            }
            $guard = 'web';
            if (Auth::getDefaultDriver() === 'sanctum') {
                $guard = 'sanctum';
            }

            $role = $user->roles->first();
            if (! $role) {
                return false;
            }
            $permissions = $role->permissions()->where('guard_name', $guard)->pluck('name')->toArray();
            if (! in_array($ability, $permissions)) {
                return false;
            }

            return true;
        });

        ResetPassword::createUrlUsing(function ($notifiable, $token) {
            /** @var Tenant $tenant */
            $tenant = Tenant::whereHas('user', fn ($q) => $q->where('email', $notifiable->getEmailForPasswordReset()))->first();
            $domaaain = $tenant->domains()->first()->domain;

            return "https://$domaaain/reset-password/$token?email=".urlencode($notifiable->getEmailForPasswordReset());
        });
    }
}
