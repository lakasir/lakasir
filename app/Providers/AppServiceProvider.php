<?php

namespace App\Providers;

use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
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
        Builder::macro('filter', function (Request $request)
        {
            /* WIP:  <07-08-22, sheenazien8> */
            $columns = $request->filters;
            if ($columns) {
                foreach ($columns as $filterColumn) {
                    if ($filterColumn['condition'] == "like") {
                        $column = "%". $filterColumn['column'] . "%";
                    } else {
                        $column = $filterColumn['column'];
                    }

                    if ($filterColumn['condition'] == "equals") {
                        $condition = "=";
                    } else {
                        $condition = $filterColumn['condition'];
                    }
                    $value = $filterColumn['value'];
                    if (!$value) {
                        return $this;
                    }
                }
            }

            return $columns ? $this->where($column, $condition, $value) : $this;
        });
    }
}
