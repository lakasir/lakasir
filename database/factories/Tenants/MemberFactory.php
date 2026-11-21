<?php

namespace Database\Factories\Tenants;

use App\Models\Tenants\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Member>
 */
class MemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'code' => 'CUS0001',
            'address' => $this->faker->streetAddress,
            'email' => $this->faker->email,
        ];
    }
}
