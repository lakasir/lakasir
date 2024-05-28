<?php

namespace App\Filament\Tenant\Pages;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Pages\Page;

class SellingReport extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.tenant.pages.selling-report';

    public function form(Form $form): Form
    {
        return $form->schema([
            DatePicker::make('start_date')
                ->native(false),
            DatePicker::make('end_date')
                ->native(false),
        ])
            ->columns(2);
    }

    public function generate()
    {
        dd('OK');
    }
}
