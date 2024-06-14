<?php

namespace App\Filament\Tenant\Resources\PurchasingResource\Pages;

use App\Constants\PurchasingStatus;
use App\Filament\Tenant\Resources\PurchasingResource;
use App\Filament\Tenant\Resources\PurchasingResource\RelationManagers\StocksRelationManager;
use App\Filament\Tenant\Resources\Traits\RefreshThePage;
use App\Models\Tenants\Purchasing;
use App\Services\Tenants\PurchasingService;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Arr;

class ViewPurchasing extends ViewRecord
{
    use RefreshThePage;

    protected static string $resource = PurchasingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('update_status')
                ->form([
                    Select::make('status')
                        ->required()
                        ->default($this->record->status)
                        ->options(Arr::where(PurchasingStatus::all(), function ($data, $key) {
                            if ($key == PurchasingStatus::approved) {
                                return can('approve purchasing');
                            }

                            return true;
                        })),
                ])
                ->action(function ($data, Purchasing $purchasing, PurchasingService $purchasingService) {
                    $purchasingService->updateStatus($purchasing, $data['status']);
                    Notification::make('success')
                        ->title(__('Status updated'))
                        ->success()
                        ->send();
                    $this->refreshPage();
                })
                ->color('warning')
                ->visible(function (Purchasing $purchasing) {
                    return $purchasing->status != PurchasingStatus::approved;
                }),
            Actions\EditAction::make()
                ->visible(fn (Purchasing $purchasing) => $purchasing->status != PurchasingStatus::approved),
            Actions\DeleteAction::make()
                ->visible(fn (Purchasing $purchasing) => $purchasing->status != PurchasingStatus::approved),
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
