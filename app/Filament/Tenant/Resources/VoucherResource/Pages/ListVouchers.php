<?php

namespace App\Filament\Tenant\Resources\VoucherResource\Pages;

use App\Filament\Tenant\Resources\VoucherResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVouchers extends ListRecords
{
    protected static string $resource = VoucherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
