<?php

namespace App\Filament\Tenant\Resources\StockOpnameResource\Pages;

use App\Filament\Tenant\Resources\StockOpnameResource;
use App\Filament\Tenant\Resources\Traits\HasItemData;
use App\Filament\Tenant\Resources\Traits\RedirectToIndex;
use App\Models\Tenants\StockOpname;
use App\Services\Tenants\StockOpnameService;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewStockOpname extends ViewRecord
{
    use HasItemData, RedirectToIndex;

    protected static string $resource = StockOpnameResource::class;

    private StockOpnameService $sOService;

    public function __construct()
    {
        $this->sOService = new StockOpnameService();
    }

    protected function loads(): array
    {
        return [
            'stockOpnameItems',
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->action(fn (StockOpname $sO) => $this->sOService->delete($sO)),
            Actions\EditAction::make(),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return '#'.$this->getRecord()->number;
    }
}
