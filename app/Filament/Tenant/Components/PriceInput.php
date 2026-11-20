<?php

namespace App\Filament\Tenant\Components;

use App\Models\Tenants\Setting;
use Filament\Forms\Components\TextInput;
use Filament\Support\RawJs;

class PriceInput
{
    public static function make(string $name): TextInput
    {
        return TextInput::make($name)
            ->translateLabel()
            ->mask(RawJs::make('$money($input)'))
            ->stripCharacters(',')
            ->numeric()
            ->prefix(Setting::get('currency', 'IDR'))
            ->required();
    }
}
