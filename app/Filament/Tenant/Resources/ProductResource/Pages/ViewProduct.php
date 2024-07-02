<?php

namespace App\Filament\Tenant\Resources\ProductResource\Pages;

use App\Features\PrintProductLabel;
use App\Filament\Tenant\Resources\ProductResource;
use App\Filament\Tenant\Resources\ProductResource\RelationManagers\SellingDetailsRelationManager;
use App\Filament\Tenant\Resources\ProductResource\RelationManagers\StocksRelationManager;
use App\Filament\Tenant\Resources\ProductResource\Widgets\StatsProduct;
use App\Filament\Tenant\Resources\Traits\RefreshThePage;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\ActionSize;

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
                Actions\DeleteAction::make(),
                Action::make(__('Print label'))
                    ->icon('heroicon-s-printer')
                    ->visible(can('can print label') && feature(PrintProductLabel::class))
                    ->form([
                        TextInput::make('count')
                            ->translateLabel()
                            ->default(0),
                    ])
                    ->action(fn ($data) => $this->printLabel($data)),
                Action::make($this->record->show ? __('Inactivate') : __('Activate'))
                    ->icon($this->record->show ? 'heroicon-s-x-circle' : 'heroicon-s-rocket-launch')
                    ->action('toggleShow'),
            ])
                ->label(__('More actions'))
                ->icon('heroicon-m-ellipsis-vertical')
                ->size(ActionSize::Small)
                ->color('primary')
                ->button(),
        ];
    }

    public function printLabel($data): void
    {
        $this->redirect(route('product-label.generate', [
            'product' => $this->record,
            'count' => $data['count'],
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
