<?php

namespace App\Filament\Tenant\Resources\StockOpnameResource\Pages;

use App\Filament\Tenant\Resources\StockOpnameResource;
use App\Filament\Tenant\Resources\Traits\HasItemData;
use App\Filament\Tenant\Resources\Traits\RedirectToIndex;
use App\Models\Tenants\StockOpname;
use App\Services\Tenants\StockOpnameService;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

class EditStockOpname extends EditRecord
{
    use HasItemData;
    use RedirectToIndex;

    protected static string $resource = StockOpnameResource::class;

    private StockOpnameService $stockOpnameService;

    public function __construct()
    {
        $this->stockOpnameService = new StockOpnameService();
    }

    protected function loads(): array
    {
        return ['stockOpnameItems'];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return '#'.$this->getRecord()->number;
    }

    protected function handleRecordUpdate(StockOpname|Model $record, array $data): Model
    {
        return $this->stockOpnameService->update($record, $data);
    }
}
