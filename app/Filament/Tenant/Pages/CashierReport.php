<?php

namespace App\Filament\Tenant\Pages;

use App\Traits\HasTranslatableResource;
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
    use HasTranslatableResource, InteractsWithFormActions, InteractsWithForms;

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
                ->translateLabel()
                ->date()
                ->native(false),
            DatePicker::make('end_date')
                ->translateLabel()
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

    public function generate()
    {
        return $this->redirectRoute('cashier-report.generate', $this->data);
    }
}
