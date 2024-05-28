<?php

namespace App\Filament\Tenant\Resources\VoucherResource\Pages;

use App\Filament\Tenant\Resources\VoucherResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateVoucher extends CreateRecord
{
    protected static string $resource = VoucherResource::class;
}
