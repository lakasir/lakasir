<?php

namespace App\Filament\Tenant\Components;

use App\Models\Tenants\Profile;
use App\Models\Tenants\Setting;
use Filament\Tables\Columns\TextColumn;

class PriceTextColumn
{
    public static function make(string $name): TextColumn
    {
        return TextColumn::make($name)
            ->translateLabel()
            ->money(
                currency: Setting::get('currency', 'IDR'),
                locale: Profile::get()->locale ?? 'en'
            );
    }
}
