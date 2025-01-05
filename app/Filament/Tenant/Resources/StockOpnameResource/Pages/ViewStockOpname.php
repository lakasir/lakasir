<?php

namespace App\Filament\Tenant\Resources\StockOpnameResource\Pages;

use App\Constants\StockOpnameStatus;
use App\Filament\Tenant\Resources\StockOpnameResource;
use App\Filament\Tenant\Resources\StockOpnameResource\RelationManagers\StockOpnameItemsRelationManager;
use App\Filament\Tenant\Resources\Traits\HasItemData;
use App\Filament\Tenant\Resources\Traits\RedirectToIndex;
use App\Filament\Tenant\Resources\Traits\RefreshThePage;
use App\Models\Tenants\Product;
use App\Models\Tenants\StockOpname;
use App\Models\Tenants\StockOpnameItem;
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

    protected static string $view = 'filament.tenant.resources.stock-opname.pages.view-record';

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
                        ->options(Arr::where(StockOpnameStatus::all()->toArray(), function ($data, $key) {
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
                ->action(function (StockOpname $sO) {
                    if ($this->record->user_id != auth()->id()) {
                        Notification::make()
                            ->title(__('PIC should be same with logged user'))
                            ->warning()
                            ->send();

                        return;
                    }
                    $this->sOService->delete($sO);
                    $this->redirect(static::getResource()::getUrl('index'));
                }),
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

    public function storeProductUsingBarcode(string $barcode): void
    {
        if ($this->getRecord()->status == StockOpnameStatus::approved) {
            Notification::make()
                ->title(__('This stock opname has been approved'))
                ->warning()
                ->send();

            return;
        }
        /** @var StockOpname $stockOpname */
        $stockOpname = $this->getRecord();
        if ($stockOpname->user_id != auth()->id()) {
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
        $actual_stock = 1;

        $sOProductId = $stockOpname->stockOpnameItems()->where('product_id', $product->id);
        $sItem = new StockOpnameItem();
        if ($sOProductId->exists()) {
            $sItem = $sOProductId->first();
            $actual_stock = $sItem->actual_stock + 1;
        }
        $adjusment_stock = $product->stock - $actual_stock;
        $sItem->fill([
            'current_stock' => $product->stock,
            'actual_stock' => $actual_stock,
            'missing_stock' => $adjusment_stock,
            'adjustment_type' => 'manual_input',
        ]);
        $sItem->product()->associate($product);
        $sItem->stockOpname()->associate($stockOpname);
        $sItem->save();

        $this->refreshPage();
    }
}
