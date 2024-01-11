<?php

namespace App\Http\Controllers\Api\Tenants;

use App\Http\Controllers\Controller;
use App\Models\Tenants\PaymentMethod;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::all();

        return $this->buildResponse()
            ->setData($paymentMethods)
            ->setMessage('success get payment methods')
            ->present();
    }
}
