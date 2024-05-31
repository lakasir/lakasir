<?php

namespace App\Filament\Tenant\Resources\PaymentMethodResource\Pages;

use App\Filament\Tenant\Resources\PaymentMethodResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePaymentMethod extends CreateRecord
{
    protected static string $resource = PaymentMethodResource::class;

    protected function getRedirectUrl(): string
    {
        return '/member/payment-methods';
    }
}
