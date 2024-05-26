<?php

namespace App\Listeners;

use App\Events\SellingCreated;
use App\Services\Tenants\DebtService;

class CreateDebtIfCredit
{
    public function __construct(private DebtService $debtService)
    {
    }

    public function handle(SellingCreated $event): void
    {
        if ($event->selling->paymentMethod->is_credit) {
            $this->debtService->create($event->selling, $event->data);
        }
    }
}
