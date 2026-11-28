<?php

namespace App\Filament\Tenant\Resources\ProductResource\Traits;

use App\Models\Tenants\Product;
use Filament\Notifications\Notification;

trait TogglesProductState
{
    public static function toggleProductVisibility(Product $product): void
    {
        $product->show = ! $product->show;
        $product->save();

        Notification::make()
            ->title(__($product->show ? 'Status active' : 'Status inactive'))
            ->success()
            ->send();
    }

    public static function toggleProductNonStock(Product $product): void
    {
        $product->is_non_stock = ! $product->is_non_stock;

        if ($product->is_non_stock) {
            $product->stock = 0;
        }

        $product->save();

        Notification::make()
            ->title(__($product->is_non_stock ? 'Marked as non stock' : 'Marked as stock'))
            ->success()
            ->send();
    }
}
