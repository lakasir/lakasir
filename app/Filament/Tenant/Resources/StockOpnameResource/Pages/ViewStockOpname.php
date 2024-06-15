<?php

namespace App\Filament\Tenant\Resources\StockOpnameResource\Pages;

use App\Constants\StockOpnameStatus;
use App\Filament\Tenant\Resources\StockOpnameResource;
use App\Filament\Tenant\Resources\StockOpnameResource\RelationManagers\StockOpnameItemsRelationManager;
use App\Filament\Tenant\Resources\Traits\HasItemData;
use App\Filament\Tenant\Resources\Traits\RedirectToIndex;
use App\Filament\Tenant\Resources\Traits\RefreshThePage;
use App\Models\Tenants\StockOpname;
use App\Services\Tenants\StockOpnameService;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Arr;

class ViewStockOpname extends ViewRecord
{
    use HasItemData, RedirectToIndex, RefreshThePage;

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
            Action::make('update_status')
                ->form([
                    Select::make('status')
                        ->required()
                        ->default($this->record->status)
                        ->options(Arr::where(StockOpnameStatus::all(), function ($data, $key) {
                            if ($key == StockOpnameStatus::approved) {
                                return can('approve stock opname');
                            }

                            return true;
                        })),
                ])
                ->action(function ($data, StockOpname $so, StockOpnameService $stockOpnameService) {
                    $stockOpnameService->updateStatus($so, $data['status']);
                    Notification::make('success')
                        ->title(__('Status updated'))
                        ->success()
                        ->send();
                    $this->refreshPage();
                })
                ->color('warning')
                ->visible(function (StockOpname $so) {
                    return $so->status != StockOpnameStatus::approved;
                }),
            Actions\DeleteAction::make()
                ->visible(function (StockOpname $so) {
                    return $so->status != StockOpnameStatus::approved;
                })
                ->action(fn (StockOpname $sO) => $this->sOService->delete($sO)),
            Actions\EditAction::make()
                ->visible(function (StockOpname $so) {
                    return $so->status != StockOpnameStatus::approved;
                }),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return '#'.$this->getRecord()->number;
    }

    public function getRelationManagers(): array
    {
        return [
            StockOpnameItemsRelationManager::make(),
        ];
    }
}
