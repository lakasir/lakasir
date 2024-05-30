<?php

namespace App\Filament\Tenant\Pages;

use App\Services\Tenants\CashierReportService;
use Filament\Actions\Action;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;

class CashierReport extends Page implements HasActions, HasForms
{
    use InteractsWithFormActions;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.tenant.pages.cashier-report';

    public ?array $data = [
        'start_date' => null,
        'end_date' => null,
    ];

    public function form(Form $form): Form
    {
        return $form->schema([
            DatePicker::make('start_date')
                ->date()
                ->native(false),
            DatePicker::make('end_date')
                ->date()
                ->native(false),
        ])
            ->columns(2)
            ->statePath('data');
    }

    public function getFormActions(): array
    {
        return [
            Action::make(__('Generate'))
                ->action('generate'),
        ];
    }

    public function generate(CashierReportService $cashierReportService)
    {
        return response()->streamDownload(function () use ($cashierReportService) {
            echo $cashierReportService->generate($this->data)->stream();
        }, 'report.pdf');
    }
}
