<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Category;
use App\Models\Item;
use App\Models\Price;
use App\Models\Stock;
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

$factory->define(Price::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'initial_price' => rand(4000, 20000),
        'selling_price' => rand(20000, 40000),
        'date' => now()->format('Y-m-d'),
        'item_id' => factory(Item::class)
    ];
});

$factory->define(Stock::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'item_id' => factory(Item::class),
        'last_stock' => 0,
        'current_stock' => rand(30, 90),
        'date' => now()->format('Y-m-d')
    ];
});

$factory->define(Item::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'internal_production' => $faker->boolean,
        'unit_id' => factory(Unit::class),
        'category_id' => factory(Category::class)
    ];
});

