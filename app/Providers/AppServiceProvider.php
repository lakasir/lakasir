<?php

namespace App\Providers;

use App\Helpers\Response;
use App\Helpers\Setting;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\Artisan;
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
            'error' => 'alert-danger',
        ]);

        /**
         * FIXME: create error custome message foR extend falidation <sheenazien8 2020-06-29>
         *
         */

        Validator::extend('confirmation', function ($attribute, $value, $parameters, $validator) {
            $keyConfirmed = explode('_', request()->key)[0];

            return $value == request()->{$keyConfirmed};
        });

        $this->app->bind('ResponseHelper', function () {
            return new Response();
        });

        $this->app->bind('setting', function ()
        {
            return new Setting();
        });

        if (app()->environment() == 'testing') {
            app('debugbar')->disable();
        }
    }
}
