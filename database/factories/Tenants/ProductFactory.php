<?php

namespace Database\Factories\Tenants;

use App\Models\Tenants\Category;
use App\Models\Tenants\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tenants\Product>
 */
class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

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
            'unit' => 'PCS',
            'type' => 'product',
        ];
    }
}
