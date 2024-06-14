<?php

namespace App\Filament\Tenant\Resources\VoucherResource\Pages;

use App\Filament\Tenant\Resources\Traits\RedirectToIndex;
use App\Filament\Tenant\Resources\VoucherResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVoucher extends CreateRecord
{
    use RedirectToIndex;

    protected static string $resource = VoucherResource::class;
}
