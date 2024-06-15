<?php

namespace App\Filament\Tenant\Resources\PurchasingResource\Traits;

use App\Models\Tenants\Product;
use App\Models\Tenants\Setting;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Support\RawJs;
use Illuminate\Support\Str;

trait HasPurchasingForm
{
    public function get($product = 'stocks.product'): array
    {
        return [
            Select::make('product_id')
                ->translateLabel()
                ->native(false)
                ->placeholder(__('Search...'))
                ->relationship(name: $product, titleAttribute: 'name')
                ->searchable()
                ->live()
                ->afterStateUpdated(function (Set $set, ?string $state) {
                    $product = Product::find($state);
                    if ($product) {
                        $set('initial_price', $product->initial_price);
                        $set('selling_price', $product->selling_price);
                    }
                }),
            TextInput::make('stock')
                ->translateLabel()
                ->afterStateUpdated(function (Set $set, Get $get, ?string $state) {
                    $set('total_initial_price', Str::of($get('initial_price'))->replace(',', '')->toInteger() * (float) $state);
                    $set('total_selling_price', Str::of($get('selling_price'))->replace(',', '')->toInteger() * (float) $state);
                })
                ->live(onBlur: true),
            DatePicker::make('expired')
                ->rule('after:now')
                ->date()
                ->native(false),
            TextInput::make('initial_price')
                ->prefix(Setting::get('currency', 'IDR'))
                ->mask(RawJs::make('$money($input)'))
                ->lte('selling_price')
                ->stripCharacters(',')
                ->numeric()
                ->required()
                ->afterStateUpdated(function (Set $set, Get $get, ?string $state) {
                    $set('total_initial_price', Str::of($state)->replace(',', '')->toInteger() * $get('stock'));
                })
                ->live(onBlur: true),
            TextInput::make('selling_price')
                ->prefix(Setting::get('currency', 'IDR'))
                ->mask(RawJs::make('$money($input)'))
                ->gte('initial_price')
                ->stripCharacters(',')
                ->numeric()
                ->required()
                ->afterStateUpdated(function (Set $set, Get $get, ?string $state) {
                    $set('total_selling_price', Str::of($state)->replace(',', '')->toInteger() * $get('stock'));
                })
                ->live(onBlur: true),
            TextInput::make('total_initial_price')
                ->mask(RawJs::make('$money($input)'))
                ->stripCharacters(',')
                ->numeric()
                ->readOnly(),
            TextInput::make('total_selling_price')
                ->mask(RawJs::make('$money($input)'))
                ->stripCharacters(',')
                ->numeric()
                ->readOnly(),
        ];
    }
}
