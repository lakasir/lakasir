<?php

namespace App\Filament\Tenant\Resources\ProductResource\Pages;

use App\Filament\Tenant\Resources\ProductResource;
use App\Models\Tenants\Product;
use App\Models\Tenants\Setting;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Alignment;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Number;
use Picqer\Barcode\BarcodeGeneratorSVG;

class PrintLabel extends Page implements HasForms
{
    protected static string $resource = ProductResource::class;

    protected static string $view = 'filament.tenant.resources.product-resource.pages.print-label';

    public $record;

    public $data = [];

    public $products = [];

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
        if ($record) {
            $this->record = Product::find($record);
        }
        $this->form->fill();
    }

    public function applySetting()
    {
        $data = $this->form->getState();
        $generator = new BarcodeGeneratorSVG();

        $product = $this->record;
        $fillable = collect();
        $barcodeString = $product->barcode ?? $product->sku;
        $barcode = $generator
            ->getBarcode($barcodeString, $generator::TYPE_CODE_128, 1, 30);
        for ($i = 0; $i < $data['qty']; $i++) {
            $fillable->push([
                'barcode' => $barcodeString,
                'barcode_html' => $barcode,
                'name' => $product->name,
                'sku' => $product->sku,
                'unit' => $product->unit,
                'price' => Number::currency($product->selling_price, Setting::get('currency', 'IDR')),
            ]);
        }

        $this->products = $fillable->toArray();
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Section::make(__('Product setting'))
                    ->footerActions([
                        Action::make('print')
                            ->hiddenLabel()
                            ->icon('heroicon-s-printer')
                            ->extraAttributes([
                                'id' => 'printLabelButton',
                            ])
                            ->translateLabel(),
                        Action::make('Apply setting')
                            ->color(Color::Green)
                            ->action('applySetting')
                            ->icon('heroicon-s-cog-6-tooth')
                            ->translateLabel(),
                    ])
                    ->footerActionsAlignment(Alignment::Between)
                    ->schema([
                        TextInput::make('product')
                            ->default($this->record->name)
                            ->readOnly(),
                        Select::make('product')
                            ->hidden($this->record != null)
                            ->required($this->record != null)
                            ->translateLabel()
                            ->options(Product::pluck('name', 'id'))
                            ->searchable(['name']),
                        TextInput::make('qty')
                            ->numeric()
                            ->extraAttributes([
                                'x-on:click' => '(e) => e.target.select()',
                            ])
                            ->default(0)
                            ->helperText(__('Define how many time the product will be printed'))
                            ->translateLabel(),
                    ]),
                Section::make(__('Print setting'))
                    ->schema([
                        Select::make('items_per_row')
                            ->options([
                                1 => 1,
                                2 => 2,
                                3 => 3,
                                4 => 4,
                            ])
                            ->default(3)
                            ->translateLabel(),
                        TextInput::make('vertical_gap')
                            ->hint(__('Pixels'))
                            ->extraAttributes([
                                'x-on:click' => '(e) => e.target.select()',
                            ])
                            ->numeric()
                            ->default(0)
                            ->translateLabel(),
                        TextInput::make('horizontal_gap')
                            ->hint(__('Pixels'))
                            ->extraAttributes([
                                'x-on:click' => '(e) => e.target.select()',
                            ])
                            ->default(0)
                            ->numeric()
                            ->translateLabel(),
                    ]),
            ]);
    }

    public function getTitle(): string|Htmlable
    {
        return __('Print Label');
    }
}
