<?php

namespace App\Traits\Suppliers;

use Filament\Tables\Columns\TextColumn;

trait HasSupplierTable
{
    public function getColumns(): array
    {
        return [
            TextColumn::make('name')
                ->translateLabel(),
            TextColumn::make('phone_number')
                ->translateLabel(),
        ];
    }

    public static function columns(): array
    {
        return (new self)->getColumns();
    }
}
