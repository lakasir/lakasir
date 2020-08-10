<?php

namespace App\Providers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (!file_exists(base_path('.env'))) {
            copy(base_path('.env.example'), base_path('.env'));
        }
        if (!env('APP_KEY')) {
            Artisan::call('key:generate');
        }
        /**
         * FIXME: create error custome message foR extend falidation <sheenazien8 2020-06-29>
         *
         */

        Validator::extend('confirmation', function ($attribute, $value, $parameters, $validator) {
            $keyConfirmed = explode('_', request()->key)[0];

            return $value == request()->{ $keyConfirmed };
        });
    }
}
