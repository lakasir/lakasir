<?php

namespace App\Traits\Suppliers;

use Filament\Forms\Components\TextInput;

trait HasSupplierForm
{
    public function getForm(): array
    {
        return [
            TextInput::make('name')
                ->translateLabel()
                ->required(),
            TextInput::make('phone_number')
                ->translateLabel()
                ->rule('regex:/^(\+?\d{1,3}[-.\s]?)?(\(?\d{3}\)?[-.\s]?)?\d{3}[-.\s]?\d{4}$/')
                ->required(),
        ];
    }

    public static function form(): array
    {
        return (new self)->getForm();
    }
}
