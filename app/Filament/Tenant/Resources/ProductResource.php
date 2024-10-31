<?php

namespace App\Filament\Tenant\Resources;

use App\Features\ProductInitialPrice;
use App\Features\ProductSku;
use App\Features\ProductStock;
use App\Features\ProductType;
use App\Filament\Tenant\Resources\ProductResource\Pages;
use App\Filament\Tenant\Resources\ProductResource\Traits\HasProductForm;
use App\Filament\Tenant\Resources\Traits\HasUploadFileField;
use App\Models\Tenants\Product;
use App\Models\Tenants\Setting;
use App\Traits\HasTranslatableResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\ActionSize;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Laravel\Pennant\Feature;

class ProductResource extends Resource
{
    use HasProductForm, HasTranslatableResource, HasUploadFileField;

    protected static ?string $model = Product::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'sku', 'barcode'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            __('Name') => $record->name,
            __('Sku') => $record->sku,
            __('Stock') => $record->stock,
        ];
    }

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        return ProductResource::getUrl('view', ['record' => $record]);
    }

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
                    ->toggleable()
                    ->translateLabel()
                    ->searchable(),
                TextColumn::make('name')
                    ->translateLabel()
                    ->searchable(['sku', 'name', 'barcode']),
                TextColumn::make('sku')
                    ->searchable()
                    ->toggleable()
                    ->visible(Feature::active(ProductSku::class)),
                TextColumn::make('barcode')
                    ->hidden()
                    ->searchable()
                    ->toggleable()
                    ->translateLabel(),
                TextColumn::make('stock')
                    ->toggleable()
                    ->translateLabel()
                    ->visible(Feature::active(ProductStock::class))
                    ->sortable(),
                TextColumn::make('unit')
                    ->toggleable()
                    ->translateLabel(),
                TextColumn::make('initial_price')
                    ->visible(Feature::active(ProductInitialPrice::class))
                    ->translateLabel()
                    ->sortable()
                    ->money(Setting::get('currency', 'IDR')),
                TextColumn::make('selling_price')
                    ->translateLabel()
                    ->sortable()
                    ->money(Setting::get('currency', 'IDR')),
                TextColumn::make('net_profit')
                    ->visible(Feature::active(ProductInitialPrice::class))
                    ->translateLabel()
                    ->sortable()
                    ->money(Setting::get('currency', 'IDR')),
                TextColumn::make('type')
                    ->visible(Feature::active(ProductType::class))
                    ->translateLabel(),
                ToggleColumn::make('is_non_stock')
                    ->toggleable()
                    ->visible(Feature::active(ProductStock::class))
                    ->translateLabel(),
            ])
            ->searchPlaceholder(__('Search (SKU, name, barcode)'))
            ->filters([
                Filter::make('expired')
                    ->toggle()
                    ->query(function (Builder $query) {
                        return $query
                            ->nearestExpiredProduct();
                    }),
                SelectFilter::make('show')
                    ->label(__('Status'))
                    ->options([
                        0 => __('Inactive'),
                        1 => __('Active'),
                    ]),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('print-label')
                        ->icon('heroicon-o-printer')
                        ->url(fn (Product $record) => static::getUrl('print-label', ['record' => $record])),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                ])
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->size(ActionSize::Small)
                    ->button()
                    ->translateLabel(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            $this->generateExpiredFormComponent(),
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\ImageEntry::make('hero_images'),
            Infolists\Components\TextEntry::make('name')
                ->translateLabel(),
            Infolists\Components\TextEntry::make('category.name')
                ->translateLabel(),
            Infolists\Components\TextEntry::make('sku')
                ->translateLabel(),
            Infolists\Components\TextEntry::make('barcode')
                ->translateLabel(),
            Infolists\Components\TextEntry::make('stock')
                ->icon(fn (int $state) => $state <= Setting::get('minimum_stock_nofication', 0) ? 'heroicon-s-exclamation-triangle' : '')
                ->iconColor(Color::Yellow)
                ->translateLabel(),
            Infolists\Components\TextEntry::make('is_non_stock')
                ->badge()
                ->getStateUsing(function (Product $product) {
                    return $product->is_non_stock ? __('Yes') : __('No');
                })
                ->color('primary')
                ->translateLabel(),
            Infolists\Components\TextEntry::make('unit')
                ->translateLabel(),
            Infolists\Components\TextEntry::make('initial_price')
                ->money(Setting::get('currency', 'IDR'))
                ->size(TextEntry\TextEntrySize::Large)
                ->translateLabel(),
            Infolists\Components\TextEntry::make('selling_price')
                ->money(Setting::get('currency', 'IDR'))
                ->size(TextEntry\TextEntrySize::Large)
                ->translateLabel(),
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
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
            'print-label' => Pages\PrintLabel::route('/{record}/print-label'),
        ];
    }
}
