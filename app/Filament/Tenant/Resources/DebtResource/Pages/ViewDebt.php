<?php

namespace App\Filament\Tenant\Resources\DebtResource\Pages;

use App\Filament\Tenant\Resources\DebtResource;
use App\Filament\Tenant\Resources\DebtResource\Traits\HasDebtPaymentForm;
use App\Filament\Tenant\Resources\Traits\RefreshThePage;
use App\Models\Tenants\Debt;
use App\Models\Tenants\DebtPayment;
use App\Services\Tenants\DebtPaymentService;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewDebt extends ViewRecord
{
    use HasDebtPaymentForm, RefreshThePage;

    protected static string $resource = DebtResource::class;

    private $dPService;

    public function __construct()
    {
        $this->dPService = new DebtPaymentService();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('add_payment')
                ->translateLabel()
                ->icon('heroicon-s-credit-card')
                ->model(DebtPayment::class)
                ->visible(function () {
                    if (! $this->record->status && can('create debt payment')) {
                        return true;
                    }

                    return false;
                })
                ->form($this->getFormPayment($this->record))
                ->action(function (array $data, Debt $debt): void {
                    $this->dPService->create($debt, $data);
                    $this->refreshPage();
                }),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        /** @var Debt $debt */
        $debt = $this->record;

        return '#'.$debt->selling->code;
    }
}
