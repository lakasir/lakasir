<?php

namespace App\Filament\Tenant\Resources\ProductResource\Traits;

use App\Features\ProductBarcode;
use App\Features\ProductExpired;
use App\Features\ProductInitialPrice;
use App\Features\ProductSku;
use App\Features\ProductStock;
use App\Features\ProductType;
use App\Models\Tenants\Category;
use App\Models\Tenants\Setting;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Set;
use Filament\Support\RawJs;
use Laravel\Pennant\Feature;

trait HasProductForm
{
    public function generateFileUploadFormComponent(): FileUpload
    {
        return FileUpload::make('hero_images')
            ->image()
            ->translateLabel()
            ->imageResizeMode('cover')
            ->imageCropAspectRatio('1:1')
            ->imageEditor()
            ->storeFileNamesIn('original_name')
            ->directory('product')
            ->imageEditorAspectRatios([
                '1:1',
                '4:3',
                '16:9',
            ])
            ->imageEditorMode(2)
            ->multiple()
            ->maxWidth('full');
    }

    public function generateCategoryFormComponent(): Select
    {
        return Select::make('category_id')
            ->translateLabel()
            ->options(Category::pluck('name', 'id'))
            ->native(false)
            ->required()
            ->createOptionForm([
                TextInput::make('name')
                    ->translateLabel()
                    ->required(),
            ])
            ->createOptionUsing(function (array $data): int {
                $category = new Category();
                $category->fill($data);
                $category->save();

                return $category->getKey();
            });
    }

    public function generateUnitFormComponent()
    {
        return TextInput::make('unit')
            ->translateLabel();
    }

    public function generateSellingPriceFormComponent(): TextInput
    {
        return TextInput::make('selling_price')
            ->translateLabel()
            ->mask(RawJs::make('$money($input)'))
            ->gte('initial_price')
            ->stripCharacters(',')
            ->numeric()
            ->prefix(Setting::get('currency', 'IDR'))
            ->required();
    }

    public function generateInitialPriceFormComponent(): TextInput
    {
        return TextInput::make('initial_price')
            ->visible(Feature::active(ProductInitialPrice::class))
            ->translateLabel()
            ->mask(RawJs::make('$money($input)'))
            ->lte('selling_price')
            ->default(0)
            // ->visible(Feature::active(Product))
            ->stripCharacters(',')
            ->numeric()
            ->prefix(Setting::get('currency', 'IDR'))
            ->required();
    }

    public function generateNameFormComponent(): TextInput
    {
        return TextInput::make('name')
            ->translateLabel()
            ->required()
            ->columnSpan(2);
    }

    public function generateSkuFormComponent(): TextInput
    {
        return TextInput::make('sku')
            ->translateLabel()
            ->visible(Feature::active(ProductSku::class))
            ->helperText(__('Leave it blank to auto generate'));
    }

    public function generateStockFormComponent(): TextInput
    {
        return TextInput::make('stock')
            ->translateLabel()
            ->numeric()
            ->visible(function ($operation) {
                return Feature::active(ProductStock::class) && $operation == 'create';
            })
            ->disabled(function ($get) {
                return $get('is_non_stock') || $get('type') == 'service';
            })
            ->required();
    }

    public function generateTypeFormComponent(): Select
    {
        return Select::make('type')
            ->translateLabel()
            ->options([
                'product' => 'Product',
                'service' => 'Service',
            ])
            ->visible(Feature::active(ProductType::class))
            ->afterStateUpdated(function (mixed $state, Set $set) {
                if ($state == 'service') {
                    $set('stock', 0);
                }
            })
            ->live()
            ->default('product')
            ->columnSpan(2)
            ->required();
    }

    public function generateBarcodeFormComponent(): TextInput
    {
        return TextInput::make('barcode')
            ->helperText(__('Point the cursor to this input first then scan the barcode'))
            ->visible(Feature::active(ProductBarcode::class))
            ->translateLabel();
    }

    public function generateNonStockFormComponent(): Checkbox
    {
        return Checkbox::make('is_non_stock')
            ->translateLabel()
            ->visible(Feature::active(ProductStock::class))
            ->live()
            ->afterStateUpdated(function ($state, $set) {
                if ($state) {
                    $set('stock', 0);
                }
            });
    }

    public function generateExpiredFormComponent()
    {
        return DatePicker::make('expired')
            ->visible(function (string $operation) {
                return Feature::active(ProductExpired::class) && $operation == 'create';
            })
            ->rule('after:now')
            ->required()
            ->native(false);
    }
}
