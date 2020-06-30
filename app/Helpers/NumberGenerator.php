<?php

namespace App\Helpers;

class NumberGenerator
{
    /**
     * @var string
     */
    private string $model;

    /**
     * @param string $model
     */
    public function __construct(string $model)
    {
        $this->model = $model;
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
        $increments = str_pad(1, 4, 0, STR_PAD_LEFT);
        $number = $number.$increments;
        $lastCompany = $this->model::latest()->first();
        if (!$lastCompany) {

        }

        return $number;
    }


}
