<?php

namespace Database\Factories;

use App\Models\Member;
use App\Models\Selling;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Selling>
 */
class SellingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Selling::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'member_id' => Member::all()->random(),
            'date' => now(),
            'payed_money' => $this->randPayedMoney(rand(0, 2)),
        ];
    }

    private function randPayedMoney($randIndex)
    {
        $payedOptionMoney = [200000, 500000, 100000];
        return $payedOptionMoney[$randIndex];
    }
}
