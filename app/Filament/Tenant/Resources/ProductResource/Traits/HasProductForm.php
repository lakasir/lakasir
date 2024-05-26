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
            ->label('Hero Images')
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
            ->options(Category::pluck('name', 'id'))
            ->native(false)
            ->searchable()
            ->required()
            ->createOptionForm([
                TextInput::make('name')
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
            ->required()
            ->columnSpan(2);
    }

    public function generateSkuFormComponent(): TextInput
    {
        return TextInput::make('sku')
            ->hint('Leave it blank to auto generate');
    }

    public function generateStockFormComponent(): TextInput
    {
        return TextInput::make('stock')
            ->numeric()
            ->required();
    }

    public function generateTypeFormComponent(): Select
    {
        return Select::make('type')
            ->options([
                'product' => 'Product',
                'service' => 'Service',
            ])
            ->default('product')
            ->columnSpan(2)
            ->required();
    }

    public function generateNonStockFormComponent(): Checkbox
    {
        return Checkbox::make('is_non_stock')
            ->label('Non Stock');
    }
}
