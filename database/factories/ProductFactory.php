<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'category_id' => Category::all()->random(),
            'name' => $this->faker->company(),
            'stock' => $this->faker->randomDigit(),
            'initial_price' => rand(50000, 60000),
            'selling_price' => rand(60000, 70000),
            'unit' => "PCS",
            "type" => "product"
        ];
    }
}
