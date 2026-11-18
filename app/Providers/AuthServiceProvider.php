<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\Tenants\Category;
use App\Models\Tenants\Member;
use App\Models\Tenants\PaymentMethod;
use App\Models\Tenants\Product;
use App\Models\Tenants\Purchasing;
use App\Models\Tenants\Receivable;
use App\Models\Tenants\ReceivablePayment;
use App\Models\Tenants\Selling;
use App\Models\Tenants\StockOpname;
use App\Models\Tenants\Supplier;
use App\Models\Tenants\Table;
use App\Models\Tenants\User;
use App\Models\Tenants\Voucher;
use App\Policies\Tenants\CategoryPolicy;
use App\Policies\Tenants\MemberPolicy;
use App\Policies\Tenants\PaymentMethodPolicy;
use App\Policies\Tenants\PermissionPolicy;
use App\Policies\Tenants\ProductPolicy;
use App\Policies\Tenants\PurchasingPolicy;
use App\Policies\Tenants\ReceivablePaymentPolicy;
use App\Policies\Tenants\ReceivablePolicy;
use App\Policies\Tenants\RolePolicy;
use App\Policies\Tenants\SellingPolicy;
use App\Policies\Tenants\StockOpnamePolicy;
use App\Policies\Tenants\SupplierPolicy;
use App\Policies\Tenants\TablePolicy;
use App\Policies\Tenants\UserPolicy;
use App\Policies\Tenants\VoucherPolicy;
use App\Tenant;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
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
        Supplier::class => SupplierPolicy::class,
        PaymentMethod::class => PaymentMethodPolicy::class,
        User::class => UserPolicy::class,
        Role::class => RolePolicy::class,
        Permission::class => PermissionPolicy::class,
        Selling::class => SellingPolicy::class,
        Purchasing::class => PurchasingPolicy::class,
        StockOpname::class => StockOpnamePolicy::class,
        Receivable::class => ReceivablePolicy::class,
        ReceivablePayment::class => ReceivablePaymentPolicy::class,
        Voucher::class => VoucherPolicy::class,
        Table::class => TablePolicy::class,
    ];

    public function register()
    {
    }

    public function boot()
    {
        $this->registerPolicies();
        Gate::after(function (User|Admin $user, $ability) {
            if ($user->is_owner) {
                return true;
            }
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
