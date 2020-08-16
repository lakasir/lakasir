<?php

use App\Models\PaymentMethod as PaymentMethodModel;
use Illuminate\Database\Seeder;

class PaymentMethod extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $paymentMethod = [
            [
                'name' => 'cash_full',
                'code' => 'CF00001',
            ],
            [
                'name' => 'cash_dp',
                'code' => 'CD00001',
            ],
            [
                'name' => 'transfer_full',
                'code' => 'TF00001',
            ],
            [
                'name' => 'transfer_dp',
                'code' => 'TD00001'
            ]
        ];
        for ($i = 0; $i < count($paymentMethod); $i++) {
            PaymentMethodModel::create($paymentMethod[$i]);
        }
    }
}
