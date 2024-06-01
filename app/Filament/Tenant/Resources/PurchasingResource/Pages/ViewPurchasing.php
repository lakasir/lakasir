<?php

namespace App\Filament\Tenant\Resources\PurchasingResource\Pages;

use App\Events\RecalculateEvent;
use App\Filament\Tenant\Resources\PurchasingResource;
use App\Filament\Tenant\Resources\PurchasingResource\RelationManagers\StocksRelationManager;
use App\Filament\Tenant\Resources\Traits\RefreshThePage;
use App\Models\Tenants\Product;
use App\Models\Tenants\Purchasing;
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
            Actions\EditAction::make()
                ->action(function (Purchasing $purchasing, array $data) {
                    $stock_id = $purchasing->stocks()->whereRaw('stock = init_stock')->get();
                    $products = null;
                    if ($stock_id->count() > 0) {
                        $products = Product::find($stock_id->pluck('product_id'));
                    }
                    $purchasing->update($data);

                    RecalculateEvent::dispatch($products, $data);
                }),
            Actions\DeleteAction::make()->action(function (Purchasing $purchasing) {
                $stock_id = $purchasing->stocks()->whereRaw('stock = init_stock')->get();
                $products = collect();
                if ($stock_id->count() > 0) {
                    $products = Product::find($stock_id->pluck('product_id'));
                }
                $purchasing->delete();

                RecalculateEvent::dispatch($products, []);
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
            StocksRelationManager::make(),
        ];
    }
}
