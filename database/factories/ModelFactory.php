<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Category;
use App\Models\Customer;
use App\Models\Group;
use App\Models\Item;
use App\Models\Price;
use App\Models\Stock;
use App\Models\Supplier;
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
        'initial_price' => rand(20, 30). '00',
        'selling_price' => rand(30, 40). '00',
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

$factory->define(Supplier::class, function (Faker $faker) {
    return [
        'name' => $faker->name(),
        'shop_name' => $faker->name(),
        'name' => $faker->name(),
        'phone' => $faker->phoneNumber(),
        'address' => $faker->streetAddress(),
        'code' => $faker->languageCode()
    ];
});

$factory->define(Group::class, function (Faker $faker) {
    return [
        'name' => $faker->name()
    ];
});

$factory->define(Customer::class, function (Faker $faker) {
    return [
        'name' => $faker->name(),
        'email' => $faker->email(),
        'code' => $faker->randomDigit()
    ];
});

$factory->define(Group::class, function (Faker $faker) {
    return [
        'name' => $faker->name(),
    ];
});
