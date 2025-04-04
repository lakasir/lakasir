<?php

namespace App\Filament\Tenant\Pages;

use App\Filament\Clusters\Reports;
use App\Services\Tenants\CashierReportService;
use App\Traits\HasTranslatableResource;
use Filament\Actions\Action;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;
use Filament\Pages\SubNavigationPosition;
use Livewire\Attributes\Url;

class CashierReport extends Page implements HasActions, HasForms
{
    use HasTranslatableResource, InteractsWithFormActions, InteractsWithForms;

    protected static ?string $title = '';

    public static ?string $label = 'Cashier Report';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.tenant.pages.cashier-report';

    protected static ?string $cluster = Reports::class;

    #[Url]
    public ?array $data = [
        'start_date' => null,
        'end_date' => null,
    ];

    public $reports = null;

    public function mount()
    {
        $this->generate(new CashierReportService);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            DatePicker::make('start_date')
                ->translateLabel()
                ->date()
                ->translateLabel()
                ->required()
                ->closeOnDateSelection()
                ->default(now())
                ->native(false),
            DatePicker::make('end_date')
                ->translateLabel()
                ->date()
                ->translateLabel()
                ->closeOnDateSelection()
                ->required()
                ->default(now())
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
            Action::make(__('Print'))
                ->color('warning')
                ->extraAttributes([
                    'id' => 'print-btn',
                ])
                ->icon('heroicon-o-printer'),
            Action::make('download-pdf')
                ->label(__('Download as PDF'))
                ->action('downloadPdf')
                ->color('warning')
                ->icon('heroicon-o-arrow-down-on-square'),
        ];
    }

    public function generate(CashierReportService $cashierReportService)
    {
        $this->validate([
            'data.start_date' => 'required',
            'data.end_date' => 'required',
        ]);

        $this->reports = $cashierReportService->generate($this->data);
    }

    public function downloadPdf()
    {
        $this->validate([
            'data.start_date' => 'required',
            'data.end_date' => 'required',
        ]);

        return $this->redirectRoute('cashier-report.generate', $this->data);
    }

    public function getSubNavigationPosition(): SubNavigationPosition
    {
        return (new static::$cluster)->getSubNavigationPosition();
    }
}
