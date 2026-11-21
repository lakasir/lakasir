<?php

namespace App\Filament\Tenant\Resources\ProductResource\Pages;

use App\Filament\Tenant\Resources\ProductResource;
use App\Filament\Tenant\Resources\Traits\RedirectToIndex;
use App\Models\Tenants\Barcode;
use App\Models\Tenants\Product;
use App\Services\Tenants\ProductService;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    use RedirectToIndex;

    protected static string $resource = ProductResource::class;

    private ProductService $productService;
    private array $barcodesData = [];

    public function __construct()
    {
        $this->productService = new ProductService();
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->barcodesData = $data['barcodes'] ?? [];
        unset($data['barcodes']);

        return $data;
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

        if (!empty($this->barcodesData)) {
            // The observer wrote a fallback barcode; the form's codes replace
            // it, so the product has every code it was created with and only
            // those.
            $product->barcodes()->delete();

            foreach ($this->barcodesData as $barcodeData) {
                if (!empty($barcodeData['code'])) {
                    $product->barcodes()->create([
                        'code' => $barcodeData['code'],
                        'type' => $barcodeData['type'] ?? 'primary',
                        'description' => $barcodeData['description'] ?? null,
                        'is_active' => true,
                    ]);
                }
            }
        }
    }
}
