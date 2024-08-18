<?php

namespace App\Listeners;

use App\Events\SellingCreated;
use App\Services\Tenants\ReceivableService;

class CreateReceivableIfCredit
{
    public function __construct(private ReceivableService $receivableService)
    {
    }

    public function handle(SellingCreated $event): void
    {
        if ($event->selling->paymentMethod->is_credit) {
            $this->receivableService->create($event->selling, $event->data);
        }
    }
}
