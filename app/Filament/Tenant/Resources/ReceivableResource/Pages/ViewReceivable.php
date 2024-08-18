<?php

namespace App\Filament\Tenant\Resources\ReceivableResource\Pages;

use App\Filament\Tenant\Resources\ReceivableResource;
use App\Filament\Tenant\Resources\ReceivableResource\Traits\HasReceivablePaymentForm;
use App\Filament\Tenant\Resources\Traits\RefreshThePage;
use App\Models\Tenants\Receivable;
use App\Models\Tenants\ReceivablePayment;
use App\Services\Tenants\ReceivablePaymentService;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewReceivable extends ViewRecord
{
    use HasReceivablePaymentForm, RefreshThePage;

    protected static string $resource = ReceivableResource::class;

    private $dPService;

    public function __construct()
    {
        $this->dPService = new ReceivablePaymentService();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('add_payment')
                ->translateLabel()
                ->icon('heroicon-s-credit-card')
                ->model(ReceivablePayment::class)
                ->visible(function () {
                    if (! $this->record->status && can('create receivable payment')) {
                        return true;
                    }

                    return false;
                })
                ->form($this->getFormPayment($this->record))
                ->action(function (array $data, Receivable $receivable): void {
                    $this->dPService->create($receivable, $data);
                    $this->refreshPage();
                }),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        /** @var receivable $receivable */
        $receivable = $this->record;

        return '#'.$receivable->selling->code;
    }
}
