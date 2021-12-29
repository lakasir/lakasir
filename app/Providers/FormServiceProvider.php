<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Sheenazien8\LivewireComponents\Facades\Builder;

class FormServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        Builder::register([
            'user-form' => \App\Forms\UserForm::class,
            'supplier-form' => \App\Forms\SupplierForm::class,
            'login-form' => \App\Forms\LoginForm::class,
            'profile-form' => \App\Forms\ProfileForm::class,
            'change-password-form' => \App\Forms\ChangePasswordForm::class,
            'customer-form' => \App\Forms\CustomerForm::class,
            'item-form' => \App\Forms\ItemForm::class,
            'edit-price-item-form' => \App\Forms\EditPriceStockForm::class,
        ]);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
