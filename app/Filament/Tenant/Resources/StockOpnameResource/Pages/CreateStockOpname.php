<?php

namespace App\Filament\Tenant\Resources\StockOpnameResource\Pages;

use App\Filament\Tenant\Resources\StockOpnameResource;
use App\Models\Tenants\StockOpname;
use App\Services\Tenants\StockOpnameService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

class CreateStockOpname extends CreateRecord
{
    protected static string $resource = StockOpnameResource::class;

    private StockOpnameService $stockOpnameService;

    protected static bool $canCreateAnother = false;

    private string $prefix;

    public function __construct()
    {
        $this->stockOpnameService = new StockOpnameService();
        $this->prefix = 'SO';
    }

    protected function handleRecordCreation(array $data): Model|StockOpname
    {
        $data['number'] = $this->stockOpnameService->generateNumber($this->prefix);

        return $this->stockOpnameService->create($data);
    }

    public function getTitle(): string|Htmlable
    {
        return '#'.$this->stockOpnameService->generateNumber($this->prefix);
    }
}
