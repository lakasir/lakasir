<?php

namespace App\Filament\Tenant\Resources\ProductResource\Pages;

use App\Features\PrintProductLabel;
use App\Features\ProductStock;
use App\Filament\Tenant\Resources\ProductResource;
use App\Filament\Tenant\Resources\ProductResource\RelationManagers\PriceUnitsRelationManager;
use App\Filament\Tenant\Resources\ProductResource\RelationManagers\SellingDetailsRelationManager;
use App\Filament\Tenant\Resources\ProductResource\RelationManagers\StocksRelationManager;
use App\Filament\Tenant\Resources\ProductResource\Widgets\StatsProduct;
use App\Filament\Tenant\Resources\Traits\RefreshThePage;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\ActionSize;
use Laravel\Pennant\Feature;

class ViewProduct extends ViewRecord
{
    use RefreshThePage;

    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make(__($this->record->show ? 'Status active' : 'Status inactive'))
                ->badge()
                ->badgeColor(Color::Red),
            ActionGroup::make([
                Actions\EditAction::make(),
                Action::make(__('Print label'))
                    ->icon('heroicon-s-printer')
                    ->visible(can('can print label') && feature(PrintProductLabel::class))
                    ->action(fn ($data) => $this->printLabel($data)),
                Action::make($this->record->show ? __('Inactivate') : __('Activate'))
                    ->icon($this->record->show ? 'heroicon-s-x-circle' : 'heroicon-s-rocket-launch')
                    ->action('toggleShow'),
                Actions\DeleteAction::make(),
            ])
                ->label(__('More actions'))
                ->icon('heroicon-m-ellipsis-vertical')
                ->size(ActionSize::Small)
                ->color('primary')
                ->button(),
        ];
    }

    public function printLabel(): void
    {
        $this->redirect($this->getResource()::getUrl('print-label', [
            'record' => $this->record,
        ]));
    }

    public function toggleShow(): void
    {
        $this->record->show = ! $this->record->show;
        $this->record->save();

        Notification::make()
            ->title(__($this->record->show ? 'Status active' : 'Status inactive'))
            ->success()
            ->send();

        $this->refreshPage();
    }

    protected function getHeaderWidgets(): array
    {
        return [
            StatsProduct::make(['product' => $this->record]),
        ];
    }

    public function getRelationManagers(): array
    {
        $relations = [
            SellingDetailsRelationManager::make(),
            PriceUnitsRelationManager::make(),
        ];
        if (! $this->record->is_non_stock && Feature::active(ProductStock::class)) {
            return [
                StocksRelationManager::make(),
                ...$relations,
            ];
        }

        return $relations;
    }
}
