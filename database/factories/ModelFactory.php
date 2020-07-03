<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Category;
use App\Models\Unit;
use Faker\Generator as Faker;

$factory->define(Unit::class, function (Faker $faker) {
    return [
        'name' => $faker->name
    ];
});

$factory->define(Category::class, function (Faker $faker) {
    return [
        'name' => $faker->name
    ];
});
