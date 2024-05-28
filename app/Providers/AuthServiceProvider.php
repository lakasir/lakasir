<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\Tenants\Category;
use App\Models\Tenants\Debt;
use App\Models\Tenants\DebtPayment;
use App\Models\Tenants\Member;
use App\Models\Tenants\Product;
use App\Models\Tenants\Purchasing;
use App\Models\Tenants\StockOpname;
use App\Models\Tenants\User;
use App\Models\Tenants\Voucher;
use App\Policies\Tenants\CategoryPolicy;
use App\Policies\Tenants\DebtPaymentPolicy;
use App\Policies\Tenants\DebtPolicy;
use App\Policies\Tenants\MemberPolicy;
use App\Policies\Tenants\PermissionPolicy;
use App\Policies\Tenants\ProductPolicy;
use App\Policies\Tenants\PurchasingPolicy;
use App\Policies\Tenants\RolePolicy;
use App\Policies\Tenants\StockOpnamePolicy;
use App\Policies\Tenants\UserPolicy;
use App\Policies\Tenants\VoucherPolicy;
use App\Tenant;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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
        User::class => UserPolicy::class,
        Role::class => RolePolicy::class,
        Permission::class => PermissionPolicy::class,
        Purchasing::class => PurchasingPolicy::class,
        StockOpname::class => StockOpnamePolicy::class,
        Debt::class => DebtPolicy::class,
        DebtPayment::class => DebtPaymentPolicy::class,
        Voucher::class => VoucherPolicy::class,
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
