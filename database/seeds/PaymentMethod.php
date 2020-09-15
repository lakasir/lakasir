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
        PaymentMethodModel::truncate();
        $json = json_encode([
            'purchasing' => true,
            'selling' => true
        ]);
        $paymentMethod = [
            [
                'name' => 'cash_full',
                'code' => 'CF00001',
                'visible_in' => $json
            ],
            [
                'name' => 'cash_dp',
                'code' => 'CD00001',
                'visible_in' => $json
            ],
            [
                'name' => 'transfer_full',
                'code' => 'TF00001',
                'visible_in' => $json
            ],
            [
                'name' => 'transfer_dp',
                'code' => 'TD00001',
                'visible_in' => $json
            ]
        ];
        for ($i = 0; $i < count($paymentMethod); $i++) {
            PaymentMethodModel::create($paymentMethod[$i]);
        }
    }
}
