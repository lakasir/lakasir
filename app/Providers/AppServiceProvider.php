<?php

namespace App\Providers;

use App\Helpers\Response;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

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
    public function boot(Dispatcher $events)
    {
        if (!file_exists(base_path('.env'))) {
            copy(base_path('.env.example'), base_path('.env'));
        }
        if (!env('APP_KEY')) {
            Artisan::call('key:generate');
        }
        \Spatie\Flash\Flash::levels([
            'success' => 'alert-success',
            'warning' => 'alert-warning',
            'error' => 'alert-error',
        ]);

        /**
         * FIXME: create error custome message foR extend falidation <sheenazien8 2020-06-29>
         *
         */

        Validator::extend('confirmation', function ($attribute, $value, $parameters, $validator) {
            $keyConfirmed = explode('_', request()->key)[0];

            return $value == request()->{ $keyConfirmed };
        });

        $this->app->bind('ResponseHelper', function () {
            return new Response();
        });

        if (app()->environment() == 'local') {
            app('debugbar')->disable();
        }
    }
}
