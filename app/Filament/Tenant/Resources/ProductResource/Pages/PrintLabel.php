<?php

namespace App\Filament\Tenant\Resources\ProductResource\Pages;

use App\Filament\Tenant\Resources\ProductResource;
use App\Models\Tenants\Product;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Alignment;

class PrintLabel extends Page implements HasForms
{
    protected static string $resource = ProductResource::class;

    protected static string $view = 'filament.tenant.resources.product-resource.pages.print-label';

    public $record;

    public $data = [];

    public function getBreadcrumbs(): array
    {
        return [
            $this->getResource()::getUrl('index') => __('Products'),
            $this->getResource()::getUrl('view', ['record' => $this->record]) => __('View'),
            __('Print Label'),
        ];
    }

    public function mount($record)
    {
        $this->record = $record;
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Section::make('Product setting')
                    ->translateLabel()
                    ->footerActions([
                        Action::make('print')
                            ->hiddenLabel()
                            ->icon('heroicon-s-printer')
                            ->translateLabel()
                            ->action(function () {
                                dd($this->form->getState());
                            }),
                        Action::make('Apply setting')
                            ->color(Color::Green)
                            ->icon('heroicon-s-cog-6-tooth')
                            ->translateLabel()
                            ->action(function () {
                                dd($this->data);
                            }),
                    ])
                    ->footerActionsAlignment(Alignment::Between)
                    ->schema([
                        Select::make('product')
                            ->required()
                            ->translateLabel()
                            ->options(Product::pluck('name', 'id'))
                            ->searchable(),
                        TextInput::make('qty')
                            ->numeric()
                            ->default(0)
                            ->helperText(__('Define how many time the product will be printed'))
                            ->translateLabel(),
                    ]),
                Section::make('Print setting')
                    ->translateLabel()
                    ->schema([
                        TextInput::make('items_per_row')
                            ->numeric()
                            ->default(3)
                            ->maxValue(4)
                            ->translateLabel(),
                        TextInput::make('vertical_gap')
                            ->numeric()
                            ->translateLabel(),
                        TextInput::make('horizontal_gap')
                            ->numeric()
                            ->translateLabel(),
                        TextInput::make('barcode_height')
                            ->numeric()
                            ->translateLabel(),
                        TextInput::make('document_size')
                            ->numeric()
                            ->translateLabel(),
                    ]),
            ]);
    }
}
