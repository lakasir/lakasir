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
            $query = $this;
            if ($columns) {
                foreach ($columns as $filterColumn) {
                    $column = $filterColumn['column'];

                    if ($filterColumn['condition'] == "equals") {
                        $condition = "=";
                    } else {
                        $condition = $filterColumn['condition'];
                    }
                    if ($filterColumn['condition'] == "like") {
                        $value = "%". $filterColumn['value'] . "%";
                    } else {
                        $value = $filterColumn['value'];
                    }
                    if (!$value) {
                        return $this;
                    }
                    $query = optional($this)->where($column, $condition, $value);
                }
            }

            return $columns ? $query : $this;
        });
    }
}
