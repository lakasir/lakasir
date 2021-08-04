<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;

/**
 * Interface Options
 * @author sheenazien8
 */
interface Options
{
    /** @return array[]|array|string[] */
    public function addOptionsBuilder(Model $model): array;
}
