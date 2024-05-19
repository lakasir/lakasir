<?php

namespace App\Filament\Tenant\Resources\DebtResource\Pages;

use App\Filament\Tenant\Resources\DebtResource;
use App\Filament\Tenant\Resources\DebtResource\Traits\HasDebtPaymentForm;
use App\Filament\Tenant\Resources\Traits\RefreshThePage;
use App\Models\Tenants\Debt;
use App\Models\Tenants\DebtPayment;
use App\Models\Tenants\Setting;
use App\Services\Tenants\DebtPaymentService;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
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
                ->icon('heroicon-s-credit-card')
                ->model(DebtPayment::class)
                ->visible(function () {
                    if (! $this->record->status && Filament::auth()->user()->can('create debt payment')) {
                        return true;
                    }

                    return false;
                })
                ->form($this->getFormPayment())
                ->action(function (array $data, Debt $debt): void {
                    $this->dPService->create($debt, $data);
                    $this->refreshPage();
                }),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            TextEntry::make('member.name')
                ->label(__('Member Name')),
            TextEntry::make('member.email')
                ->label(__('Contact Member')),
            TextEntry::make('total_debt')
                ->money(Setting::get('currency', 'IDR')),
            TextEntry::make('rest_debt')
                ->money(Setting::get('currency', 'IDR')),
            TextEntry::make('due_date')
                ->date(),
            TextEntry::make('total_billing_via_whatsapp'),
            TextEntry::make('last_billing_date')
                ->date(),
            TextEntry::make('status')
                ->getStateUsing(function (Debt $debt) {
                    return $debt->status ? 'Paid off' : 'Unpaid';
                })
                ->badge()
                ->iconColor(fn (string $state): string => match ($state) {
                    'Unpaid' => 'danger',
                    'Paid off' => 'success',
                })
                ->color(fn (string $state): string => match ($state) {
                    'Unpaid' => 'danger',
                    'Paid off' => 'success',
                })
                ->icon(fn (string $state): string => match ($state) {
                    'Paid off' => 'heroicon-o-check-circle',
                    'Unpaid' => 'heroicon-o-exclamation-circle',
                }),
        ]);

    }

    public function getTitle(): string|Htmlable
    {
        /** @var Debt $debt */
        $debt = $this->record;

        return '#'.$debt->selling->code;
    }
}
