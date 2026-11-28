<?php

namespace App\Providers;

use App\Models\Company;
use App\Models\Tenants\User;
use App\Services\Tenants\CheckoutProducts;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Laravel\Pennant\Feature;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Authenticatable::class, User::class);

        // One product/price-unit cache per request: the checkout's rule, total
        // calculation and detail writer all ask it for the same rows.
        $this->app->scoped(CheckoutProducts::class);

        if ($this->app->environment('local', 'development') && class_exists(IdeHelperServiceProvider::class)) {
            $this->app->register(IdeHelperServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Builder::macro('filter', function (Request $request) {
            /* WIP:  <07-08-22, sheenazien8> */
            $columns = $request->filters;
            $query = $this;
            if ($columns) {
                foreach ($columns as $filterColumn) {
                    $column = $filterColumn['column'];

                    if ($filterColumn['condition'] == 'equals') {
                        $condition = '=';
                    } else {
                        $condition = $filterColumn['condition'];
                    }
                    if ($filterColumn['condition'] == 'like') {
                        $value = '%'.$filterColumn['value'].'%';
                    } else {
                        $value = $filterColumn['value'];
                    }
                    if (! $value) {
                        return $this;
                    }
                    $query = optional($this)->where($column, $condition, $value);
                }
            }

            return $columns ? $query : $this;
        });
        Feature::resolveScopeUsing(fn ($driver) => Company::getCurrent());
        Feature::discover();
    }
}
