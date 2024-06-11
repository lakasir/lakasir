<?php

namespace App\Filament\Tenant\Resources\ProductResource\Traits;

use App\Models\Tenants\Category;
use App\Models\Tenants\Product;
use App\Models\Tenants\Setting;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Support\RawJs;

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
            ->searchable()
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
            ->translateLabel()
            ->datalist(
                Product::all()
                    ->pluck('unit')
                    ->unique()
                    ->toArray()
            );

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
            ->translateLabel()
            ->mask(RawJs::make('$money($input)'))
            ->lte('selling_price')
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
            ->hint(__('Leave it blank to auto generate'));
    }

    public function generateStockFormComponent(): TextInput
    {
        return TextInput::make('stock')
            ->translateLabel()
            ->numeric()
            ->disabled(function ($get) {
                return $get('is_non_stock');
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
            ->default('product')
            ->columnSpan(2)
            ->required();
    }

    public function generateBarcodeFormComponent(): TextInput
    {
        return TextInput::make('barcode')
            ->translateLabel();
    }

    public function generateNonStockFormComponent(): Checkbox
    {
        return Checkbox::make('is_non_stock')
            ->translateLabel()
            ->live()
            ->afterStateUpdated(function ($state, $set) {
                if ($state) {
                    $set('stock', 0);
                }
            });
    }
}
