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
     * @var string
     */
    private $prefix;


    /**
     * @param string $model
     */
    public function __construct(string $model, string $prefix)
    {
        $this->model = new $model;
        $this->prefix = $prefix;
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

        $prefix = $this->prefix;
        $number = $prefix . now()->format('ymd');
        $lastInsert = $this->model::latest()->first();
        $increments = str_pad(($lastInsert->id ?? 1), 4, "0", STR_PAD_LEFT);
        $format = $number.$increments;
        return $format;
    }
}
