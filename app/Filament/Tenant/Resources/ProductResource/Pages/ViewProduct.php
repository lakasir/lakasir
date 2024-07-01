<?php

namespace App\Filament\Tenant\Resources\ProductResource\Pages;

use App\Filament\Tenant\Resources\ProductResource\RelationManagers\SellingDetailsRelationManager;
use App\Filament\Tenant\Resources\ProductResource\RelationManagers\StocksRelationManager;
use App\Filament\Tenant\Resources\ProductResource\Widgets\StatsProduct;
use App\Filament\Tenant\Resources\ProductsResource;
use App\Filament\Tenant\Resources\Traits\RefreshThePage;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Support\Colors\Color;

class ViewProduct extends ViewRecord
{
    use RefreshThePage;

    protected static string $resource = ProductsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make($this->record->show ? __('Inactivate') : __('Activate'))
                ->action('toggleShow')
                ->color(Color::Teal),
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    public function toggleShow(): void
    {
        $this->record->show = ! $this->record->show;
        $this->record->save();

        Notification::make()
            ->title(__('Update success'))
            ->success()
            ->send();

        $this->refreshPage();
    }

    protected function getHeaderWidgets(): array
    {
        return [
            StatsProduct::make(['recordId' => $this->record->id]),
        ];
    }

    public function getRelationManagers(): array
    {
        $relations = [
            SellingDetailsRelationManager::make(),
        ];
        if (! $this->record->is_non_stock) {
            return [
                RelationGroup::make('', array_merge(
                    [
                        StocksRelationManager::make(),
                    ],
                    $relations,
                )),
            ];
        }

        return $relations;
    }
}
