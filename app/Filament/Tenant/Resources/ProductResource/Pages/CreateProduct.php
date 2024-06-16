<?php

namespace App\Filament\Tenant\Resources\ProductResource\Pages;

use App\Filament\Tenant\Resources\ProductResource;
use App\Filament\Tenant\Resources\Traits\RedirectToIndex;
use App\Models\Tenants\Product;
use App\Services\Tenants\ProductService;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    use RedirectToIndex;

    protected static string $resource = ProductResource::class;

    private ProductService $productService;

    public function __construct()
    {
        $this->productService = new ProductService();
    }

    public function afterCreate()
    {
        /** @var Product $product */
        $product = $this->record;
        $product->hero_images = $this
            ->productService
            ->handleCreateUploadedFile(
                $this->form->getState()['original_name']
            );
        $product->save();
        $stock = $product->stocks->first();
        $stock->expired = $this->data['expired'] ?? null;
        $stock->save();
    }
}
