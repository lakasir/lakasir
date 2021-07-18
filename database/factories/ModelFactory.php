<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Category;
use App\Models\Customer;
use App\Models\CustomerType;
use App\Models\Group;
use App\Models\Item;
use App\Models\Price;
use App\Models\Stock;
use App\Models\Supplier;
use Faker\Generator as Faker;

$factory->define(Category::class, function (Faker $faker) {
    return [
        'name' => $faker->name
    ];
});

$factory->define(Price::class, function (Faker $faker) {
    return [
        'initial_price' => rand(20, 30) . '00',
        'selling_price' => rand(30, 40) . '00',
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
    $pcs_rand = ['PCS', 'KG', 'L', null];
    $type_rand = [0, 1, 2, 3, 4, 5];
    return [
        'name' => $faker->name,
        'internal_production' => $faker->boolean,
        'category_id' => factory(Category::class),
        'unit' => $pcs_rand[rand(0, count($pcs_rand) - 1)],
        'sku' => $faker->randomLetter,
        'item_type' => $type_rand[rand(0, count($type_rand) - 1)]
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

$factory->define(CustomerType::class, function (Faker $faker) {
    return [
        'name' => $faker->name(),
        'default_point' => $faker->randomDigit
    ];
});

$factory->define(Customer::class, function (Faker $faker) {
    $customer_type = factory(CustomerType::class)->create();

    return [
        'name' => $faker->name(),
        'email' => $faker->email(),
        'code' => $faker->randomDigit(),
        'customer_type_id' => $customer_type->getKey()
    ];
});

$factory->define(Group::class, function (Faker $faker) {
    return [
        'name' => $faker->name(),
    ];
});
