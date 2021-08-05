<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;

/**
 * Interface Options
 * @author sheenazien8
 */
interface WithOptions
{
    /** @return array[]|array|string[] */
    public function addOptionsBuilder(Model $model): array;
}
