<?php

namespace App\Filament\Tenant\Resources;

use App\Filament\Tenant\Resources\ProductResource\Pages;
use App\Filament\Tenant\Resources\ProductResource\Traits\HasProductForm;
use App\Models\Tenants\Product;
use App\Models\Tenants\Setting;
use App\Traits\HasTranslatableResource;
use Filament\Forms\Components\BaseFileUpload;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use League\Flysystem\UnableToCheckFileExistence;

class ProductResource extends Resource
{
    use HasProductForm, HasTranslatableResource;

    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    public static function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Product::query()->with('stocks', 'category')->latest())
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->translateLabel()
                    ->searchable(),
                TextColumn::make('name')
                    ->translateLabel()
                    ->searchable(),
                TextColumn::make('sku')
                    ->translateLabel(),
                TextColumn::make('stock')
                    ->translateLabel()
                    ->sortable(),
                TextColumn::make('unit')
                    ->translateLabel(),
                TextColumn::make('initial_price')
                    ->translateLabel()
                    ->sortable()
                    ->money(Setting::get('currency', 'IDR')),
                TextColumn::make('selling_price')
                    ->translateLabel()
                    ->sortable()
                    ->money(Setting::get('currency', 'IDR')),
                TextColumn::make('net_profit')
                    ->translateLabel()
                    ->sortable()
                    ->money(Setting::get('currency', 'IDR')),
                TextColumn::make('type')
                    ->translateLabel(),
                ToggleColumn::make('is_non_stock')
                    ->translateLabel()
                    ->label('Non Stock'),

            ])
            ->filters([
                Filter::make('expired')
                    ->toggle()
                    ->query(function (Builder $query) {
                        return $query
                            ->whereHas('stocks', function (Builder $builder) {
                                return $builder
                                    ->whereDate('expired', '<=', now());
                            });
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    private function getUploadedFileUsing(BaseFileUpload $component, string $file, string|array|null $storedFileNames)
    {
        /** @var Storage $storage */
        $storage = $component->getDisk();

        $shouldFetchFileInformation = $component->shouldFetchFileInformation();

        if ($shouldFetchFileInformation) {
            try {
                if (! $storage->exists($file)) {
                    return null;
                }
            } catch (UnableToCheckFileExistence) {
                return null;
            }
        }

        return [
            'name' => $file,
            'size' => $shouldFetchFileInformation ? $storage->size($file) : 0,
            'type' => $shouldFetchFileInformation ? $storage->mimeType($file) : null,
            'url' => '/storage'.$file,
        ];
    }

    private function generateForm(): array
    {
        return [
            Grid::make()
                ->columns(3)
                ->schema([
                    Grid::make()
                        ->columns(1)
                        ->schema([
                            $this->generateFileUploadFormComponent()
                                ->getUploadedFileUsing(function (string $file, string|array|null $storedFileNames) {
                                    return $this->getUploadedFileUsing($this->generateFileUploadFormComponent(), $file, $storedFileNames);
                                }),
                        ]),
                ]),
            $this->generateNameFormComponent()
                ->columnSpan(1),
            $this->generateBarcodeFormComponent(),
            $this->generateSkuFormComponent(),
            $this->generateCategoryFormComponent(),
            $this->generateStockFormComponent(),
            $this->generateUnitFormComponent(),
            DatePicker::make('expired')
                ->rule('after:now')
                ->native(false),
            $this->generateInitialPriceFormComponent(),
            $this->generateSellingPriceFormComponent(),
            $this->generateTypeFormComponent()
                ->columnSpan(1),
            $this->generateNonStockFormComponent(),
        ];
    }

    public static function form(Form $form): Form
    {
        return $form->schema((new self)->generateForm());
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
