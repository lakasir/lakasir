<?php

namespace Database\Factories\Tenants;

use App\Models\Tenants\Product;
use App\Models\Tenants\Stock;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tenants\Stock>
 */
class StockFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Stock::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'stock' => $this->faker->numberBetween(1, 100),
            'init_stock' => $this->faker->numberBetween(1, 100),
            'initial_price' => $this->faker->numberBetween(5000, 50000),
            'selling_price' => $this->faker->numberBetween(10000, 100000),
            'type' => 'in',
            'is_ready' => true,
            'date' => $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
        ];
    }
}