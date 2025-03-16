<?php

namespace App\Filament\Tenant\Resources\PurchasingResource\Pages;

use App\Constants\PurchasingStatus;
use App\Constants\StockOpnameStatus;
use App\Filament\Tenant\Resources\PurchasingResource;
use App\Filament\Tenant\Resources\PurchasingResource\RelationManagers\StocksRelationManager;
use App\Filament\Tenant\Resources\Traits\RefreshThePage;
use App\Models\Tenants\Product;
use App\Models\Tenants\Purchasing;
use App\Services\Tenants\PurchasingService;
use App\Services\Tenants\StockService;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\ActionSize;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Arr;

class ViewPurchasing extends ViewRecord
{
    use RefreshThePage;

    protected static string $resource = PurchasingResource::class;

    protected static string $view = 'filament.tenant.resources.purchasings.pages.view-record';

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                Action::make('update_status')
                    ->form([
                        Select::make('status')
                            ->required()
                            ->default($this->record->status)
                            ->options(Arr::where(PurchasingStatus::all()->toArray(), function ($key) {
                                if ($key == PurchasingStatus::approved) {
                                    return can('approve purchasing');
                                }

                                return true;
                            })),
                    ])
                    ->action(function ($data, Purchasing $purchasing, PurchasingService $purchasingService) {
                        $purchasingService->updateStatus($purchasing, $data['status']);

                        $this->refreshPage();
                    })
                    ->icon('heroicon-s-pencil-square')
                    ->visible(function (Purchasing $purchasing) {
                        return $purchasing->status != PurchasingStatus::approved;
                    }),
                Actions\EditAction::make()
                    ->visible(fn (Purchasing $purchasing) => $purchasing->status != PurchasingStatus::approved),
                Actions\DeleteAction::make()
                    ->visible(fn (Purchasing $purchasing) => $purchasing->status != PurchasingStatus::approved),

            ])
                ->label(__('More actions'))
                ->icon('heroicon-m-ellipsis-vertical')
                ->size(ActionSize::Small)
                ->color('primary')
                ->button(),
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

    public function storeProductUsingBarcode(string $barcode, PurchasingService $purchasingService, StockService $stockService): void
    {
        if ($this->getRecord()->status == StockOpnameStatus::approved) {
            Notification::make()
                ->title(__('This purchasing has been approved'))
                ->warning()
                ->send();

            return;
        }

        /** @var StockOpname $purchasing */
        $purchasing = $this->getRecord();
        if ($purchasing->user_id != auth()->id()) {
            Notification::make()
                ->title(__('PIC should be same with logged user'))
                ->warning()
                ->send();

            return;
        }

        /** @var Product $product */
        $product = Product::where('barcode', $barcode)->orWhere('sku', $barcode)->first();
        if (! $product) {
            Notification::make()
                ->title(__('Product not found'))
                ->warning()
                ->send();

            return;
        }

        /** @var Purchasing $purchasing */
        $purchasing = $this->record;
        $init_stock = 1;

        $pPProductId = $purchasing->stocks()->where('product_id', $product->getKey());
        if ($pPProductId->exists()) {
            $stock = $pPProductId->first();
            $init_stock = $stock->init_stock + 1;
            $stockService->update($stock, [
                'init_stock' => $init_stock,
                'stock' => $init_stock,
                'product_id' => $product->getKey(),
            ], $purchasing);
        } else {
            $stockService->create([
                'init_stock' => $init_stock,
                'stock' => $init_stock,
                'product_id' => $product->getKey(),
                'initial_price' => $product->initial_price,
                'selling_price' => $product->selling_price,
            ], $purchasing);
        }

        $purchasingService->update(
            $purchasing->getKey(),
            $purchasingService->getUpdatedPrice($purchasing)
        );
        $this->refreshPage();
    }
}
