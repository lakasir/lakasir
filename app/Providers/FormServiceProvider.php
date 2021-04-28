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
            'supplier-form' => \App\Forms\SupplierForm::class
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
