<?php

namespace App\Filament\Tenant\Resources\PurchasingResource\Pages;

use App\Filament\Tenant\Resources\PurchasingResource;
use App\Models\Tenants\Purchasing;
use App\Services\Tenants\PurchasingService;
use Exception;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

class CreatePurchasing extends CreateRecord
{
    protected static string $resource = PurchasingResource::class;

    private PurchasingService $purchasingService;

    protected static bool $canCreateAnother = false;

    private string $prefix;

    public function __construct()
    {
        $this->purchasingService = new PurchasingService();
        $this->prefix = 'PO';
    }

    protected function handleRecordCreation(array $data): Model|Purchasing
    {
        try {
            $data['user_id'] = auth()->id();
            $data['number'] = $this->purchasingService->generateNumber($this->prefix);
            $purchasing = $this->purchasingService->create($data);
        } catch (Exception $e) {
            throw $e;
        }

        return $purchasing;
    }

    public function getTitle(): string|Htmlable
    {
        return '#'.$this->purchasingService->generateNumber($this->prefix);
    }

    protected function getRedirectUrl(): string
    {
        return PurchasingResource::getUrl('view', [
            'record' => $this->record,
        ]);
    }
}
