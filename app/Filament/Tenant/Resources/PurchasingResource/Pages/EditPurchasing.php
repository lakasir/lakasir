<?php

namespace App\Filament\Tenant\Resources\PurchasingResource\Pages;

use App\Filament\Tenant\Resources\PurchasingResource;
use App\Models\Tenants\Purchasing;
use App\Services\Tenants\PurchasingService;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

class EditPurchasing extends EditRecord
{
    protected static string $resource = PurchasingResource::class;

    private PurchasingService $purchasingService;

    public function __construct()
    {
        $this->purchasingService = new PurchasingService();
    }

    public function getTitle(): string|Htmlable
    {
        return '#'.$this->getRecord()->number;
    }

    protected function handleRecordUpdate(Purchasing|Model $record, array $data): Model
    {
        return $this->purchasingService->update($record->getKey(), $data);
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl;
    }
}
