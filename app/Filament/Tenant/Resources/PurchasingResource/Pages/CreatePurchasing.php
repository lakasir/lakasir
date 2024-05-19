<?php

namespace App\Filament\Tenant\Resources\PurchasingResource\Pages;

use App\Filament\Tenant\Resources\PurchasingResource;
use App\Models\Tenants\Purchasing;
use App\Services\Tenants\PurchasingService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

class CreatePurchasing extends CreateRecord
{
    protected static string $resource = PurchasingResource::class;

    private PurchasingService $purchasingService;

    private string $prefix;

    public function __construct()
    {
        $this->purchasingService = new PurchasingService();
        $this->prefix = 'PO';
    }

    protected function handleRecordCreation(array $data): Model|Purchasing
    {
        $data['number'] = $this->purchasingService->generateNumber($this->prefix);

        return $this->purchasingService->create($data);
    }

    public function getTitle(): string|Htmlable
    {
        return '#'.$this->purchasingService->generateNumber($this->prefix);
    }

    protected function getRedirectUrl(): string
    {
        return PurchasingResource::getNavigationUrl();
    }
}
