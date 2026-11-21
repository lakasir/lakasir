<?php

namespace Database\Factories\Tenants;

use App\Models\PriceUnit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PriceUnit>
 */
class PriceUnitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'stock' => 10,
            'unit' => 'unit-test',
        ];
    }
}
