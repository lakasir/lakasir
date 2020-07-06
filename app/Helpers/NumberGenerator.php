<?php

namespace App\Helpers;
use Illuminate\Database\Eloquent;
use Illuminate\Support\Collection;

class NumberGenerator
{
    /**
     * @var string
     */
    private $model;

    /**
     * @param string $model
     */
    public function __construct($model)
    {
        $this->model = new $model;
    }
    /**
     * create unique number for user
     *
     * @return string
     */
    public function create(): string
    {
        /**
         * FIXME: create number generator <sheenazien 2020-06-30>
         * create dinamis increments number
         */

        $prefix = env('CODE_PREFIX');
        $number = $prefix . now()->format('ymd');
        $lastCompany = $this->model::latest()->first();
        $increments = str_pad(($lastCompany->id ?? 1), 4, "0", STR_PAD_LEFT);
        $format = $number.$increments;
        return $format;
    }


}
