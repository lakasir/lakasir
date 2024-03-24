<?php

namespace App\Filament\Tenant\Resources;

use App\Filament\Tenant\Resources\ProductResource\Pages;
use App\Models\Tenants\Category;
use App\Models\Tenants\Product;
use App\Models\Tenants\Setting;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('category_id')
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
                    }),
                TextInput::make('name')
                    ->required(),
                TextInput::make('sku')
                    ->required(),
                TextInput::make('stock')
                    ->required(),
                TextInput::make('unit')
                    ->datalist(
                        Product::all()
                            ->pluck('unit')
                            ->unique()
                            ->toArray()
                    ),
                TextInput::make('initial_price')
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->numeric()
                    ->prefix(Setting::get('currency', 'IDR'))
                    ->required(),
                TextInput::make('selling_price')
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->numeric()
                    ->prefix(Setting::get('currency', 'IDR'))
                    ->required(),
                Select::make('type')
                    ->options([
                        'product' => 'Product',
                        'service' => 'Service',
                    ])
                    ->required(),
                Checkbox::make('is_non_stock')
                    ->label('Non Stock'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label('Category')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('sku'),
                TextColumn::make('stock')
                    ->sortable(),
                TextColumn::make('unit'),
                TextColumn::make('initial_price')
                    ->sortable()
                    ->money(Setting::get('currency', 'IDR')),
                TextColumn::make('selling_price')
                    ->sortable()
                    ->money(Setting::get('currency', 'IDR')),
                TextColumn::make('type'),
                ToggleColumn::make('is_non_stock')
                    ->label('Non Stock'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
