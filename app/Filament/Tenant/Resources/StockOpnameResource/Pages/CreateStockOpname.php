<?php

namespace App\Filament\Tenant\Resources\StockOpnameResource\Pages;

use App\Filament\Tenant\Resources\StockOpnameResource;
use App\Filament\Tenant\Resources\Traits\RedirectToIndex;
use App\Models\Tenants\StockOpname;
use App\Services\Tenants\StockOpnameService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

class CreateStockOpname extends CreateRecord
{
    use RedirectToIndex;

    protected static string $resource = StockOpnameResource::class;

    private StockOpnameService $stockOpnameService;

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
