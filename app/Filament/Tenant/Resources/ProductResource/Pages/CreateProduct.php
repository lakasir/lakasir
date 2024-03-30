<?php

namespace App\Filament\Tenant\Resources\ProductResource\Pages;

use App\Filament\Tenant\Resources\ProductResource;
use App\Filament\Tenant\Resources\ProductResource\Traits\HasProductForm;
use App\Models\Tenants\Product;
use App\Services\Tenants\ProductService;
use Filament\Forms\Components\Grid;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    use HasProductForm;

    protected static string $resource = ProductResource::class;

    private ProductService $productService;

    public function __construct()
    {
        $this->productService = new ProductService();
    }

    public function generateForm(): array
    {
        return [
            Grid::make()
                ->columns(3)
                ->schema([
                    $this->generateFileUploadFormComponent(),
                    $this->generateNameFormComponent(),
                ]),
            $this->generateSkuFormComponent(),
            $this->generateCategoryFormComponent(),
            $this->generateStockFormComponent(),
            $this->generateUnitFormComponent(),
            $this->generateSellingPriceFormComponent(),
            $this->generateInitialPriceFormComponent(),
            $this->generateTypeFormComponent(),
            $this->generateNonStockFormComponent(),
        ];
    }

    protected function getForms(): array
    {
        return [
            'form' => $this->form(static::getResource()::form(
                $this->makeForm()
                    ->schema($this->generateForm())
                    ->operation('create')
                    ->model($this->getModel())
                    ->statePath($this->getFormStatePath())
                    ->columns($this->hasInlineLabels() ? 1 : 2)
                    ->inlineLabel($this->hasInlineLabels()),
            )),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return '/member/products';
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
    }
}
