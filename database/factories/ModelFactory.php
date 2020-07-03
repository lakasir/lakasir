<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Unit;
use Faker\Generator as Faker;

$factory->define(Unit::class, function (Faker $faker) {
    return [
        'name' => $faker->name
    ];
});
