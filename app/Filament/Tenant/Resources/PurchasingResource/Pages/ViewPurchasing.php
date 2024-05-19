<?php

namespace App\Filament\Tenant\Resources\PurchasingResource\Pages;

use App\Filament\Tenant\Resources\PurchasingResource;
use App\Filament\Tenant\Resources\PurchasingResource\RelationManagers\StocksRelationManager;
use App\Filament\Tenant\Resources\Traits\RefreshThePage;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewPurchasing extends ViewRecord
{
    use RefreshThePage;

    protected static string $resource = PurchasingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return '#'.$this->getRecord()->number;
    }

    public function getRelationManagers(): array
    {
        return [
            StocksRelationManager::make(),
        ];
    }
}
