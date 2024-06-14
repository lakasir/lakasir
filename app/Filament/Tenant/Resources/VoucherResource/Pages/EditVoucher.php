<?php

namespace App\Filament\Tenant\Resources\VoucherResource\Pages;

use App\Filament\Tenant\Resources\Traits\RedirectToIndex;
use App\Filament\Tenant\Resources\VoucherResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVoucher extends EditRecord
{
    use RedirectToIndex;

    protected static string $resource = VoucherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
