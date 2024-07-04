<?php

namespace App\Filament\Tenant\Pages;

use App\Traits\HasTranslatableResource;
use Filament\Actions\Action;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components;
use Filament\Forms\Components\Actions\Action as ActionsAction;
use Filament\Forms\Components\Grid;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;

class Printer extends Page implements HasActions, HasForms
{
    use HasTranslatableResource;
    use InteractsWithFormActions;

    protected static ?string $navigationIcon = 'heroicon-o-printer';

    protected static string $view = 'filament.tenant.pages.printer';

    public ?array $data = [];

    public function mount()
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Components\Textarea::make('header')
                ->rows(5)
                ->translateLabel(),
            Components\TextInput::make('name')
                ->required()
                ->translateLabel(),
            Components\Select::make('driver')
                ->default('usb')
                ->options([
                    // 'bluetooth' => 'Bluetooh',
                    'usb' => 'USB',
                ])
                ->translateLabel(),
            Grid::make(columns: 3)
                ->schema([
                    Components\TextInput::make('printer')
                        ->required()
                        ->helperText(__('Please click the select printer button to choose the connected printer'))
                        ->readOnly()
                        ->translateLabel()
                        ->columnSpan(2),
                    Components\TextInput::make('printerId')
                        ->required()
                        ->hintActions([
                            ActionsAction::make('select_printer')
                                ->icon('heroicon-o-printer')
                                ->translateLabel()
                                ->extraAttributes([
                                    'x-on:click' => 'fetchDeviceByDriver',
                                ]),
                        ])
                        ->readOnly(),
                ]),
            Components\Textarea::make('footer')
                ->rows(5)
                ->translateLabel(),
        ])->statePath('data');
    }

    public function getFormActions(): array
    {
        return [
            Action::make('save')
                ->translateLabel()
                ->extraAttributes([
                    'x-on:click' => 'save',
                ]),
            Action::make('test')
                ->translateLabel()
                ->color('warning')
                ->icon('heroicon-o-printer')
                ->extraAttributes([
                    'x-on:click' => 'test',
                ]),
        ];
    }

    public function validateInput()
    {
        $this->validate([
            'data.printer' => 'required',
            'data.name' => 'required',
        ]);
    }
}
