<?php

namespace Database\Factories\Tenants;

use App\Constants\VoucherType;
use App\Models\Voucher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Voucher>
 */
class VoucherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'voucher-1',
            'code' => '000000',
            'type' => VoucherType::flat,
            'nominal' => 2000,
            'minimal_buying' => 20000,
            'start_date' => now(),
            'expired' => now()->addDays(10),
            'kuota' => 20,
        ];
    }
}
