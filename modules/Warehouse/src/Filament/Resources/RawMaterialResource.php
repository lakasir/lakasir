<?php

namespace Modules\Warehouse\Filament\Resources;

use App\Models\Tenants\Profile;
use App\Models\Tenants\Setting;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Lakasir\LakasirModule\Abstracts\ModuleResource;
use Modules\Warehouse\Filament\Resources\RawMaterialResource\Pages;
use Modules\Warehouse\Models\ProductionRecipeItem;
use Modules\Warehouse\Models\RawMaterial;
use Modules\Warehouse\Support\Money;

class RawMaterialResource extends ModuleResource
{
    protected static ?string $model = RawMaterial::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    /**
     * Derive the base-unit figures from the purchase, mirroring the
     * `base_qty = input_qty * factor` and `price_per_base_unit = harga_total / base_qty`
     * columns of a recipe sheet.
     */
    private static function syncBasePricing(Get $get, Set $set): void
    {
        $factor = RawMaterial::unitFactor($get('input_unit'));
        // Both fields may still be money-masked strings while typing.
        $inputQty = Money::toFloat($get('input_qty'));
        $hargaTotal = Money::toFloat($get('harga_total'));

        if ($inputQty <= 0) {
            $set('base_qty', null);
            $set('base_unit', null);
            $set('price_per_base_unit', null);

            return;
        }

        $override = Money::toFloat($get('base_quantity_override'));
        $baseQty = $override > 0
            ? $override
            : ($factor !== null ? $inputQty * $factor : null);

        if ($baseQty === null) {
            $set('base_qty', null);
            $set('base_unit', null);
            $set('price_per_base_unit', null);

            return;
        }

        $set('base_qty', $baseQty);
        $set('base_unit', RawMaterial::baseUnitFor(RawMaterial::unitFamily($get('input_unit'))) ?? 'pcs');
        $set('price_per_base_unit', $hargaTotal > 0 ? $hargaTotal / $baseQty : null);
    }

    public static function getForm(): array
    {
        return [
            TextInput::make('name')
                ->translateLabel()
                ->required(),
            TextInput::make('sku')
                ->unique(
                    ignoreRecord: true
                )
                ->hint(__('Leave it blank to auto generate'))
                ->translateLabel(),

            TextInput::make('input_qty')
                ->label(__('Purchase quantity'))
                ->helperText(__('How much you bought, e.g. 1 for 1 kg, 25 for 25 kg'))
                ->numeric()
                ->minValue(0)
                ->live(onBlur: true)
                ->afterStateUpdated(fn (Get $get, Set $set) => self::syncBasePricing($get, $set))
                ->columnSpan(1)
                ->required(),
            TextInput::make('input_unit')
                ->label(__('Purchase unit'))
                ->helperText(__('kg, g, l, ml, pcs, pack, tabung, masakan…'))
                ->live(onBlur: true)
                ->afterStateUpdated(fn (Get $get, Set $set) => self::syncBasePricing($get, $set))
                ->columnSpan(1)
                ->required(),
            TextInput::make('harga_total')
                ->label(__('Total price paid'))
                ->mask(RawJs::make('$money($input)'))
                ->stripCharacters(',')
                ->numeric()
                ->prefix(Setting::get('currency', 'IDR'))
                ->live(onBlur: true)
                ->afterStateUpdated(fn (Get $get, Set $set) => self::syncBasePricing($get, $set))
                ->columnSpan(1)
                ->required(),

            TextInput::make('base_quantity_override')
                ->label(__('Unpacked quantity (for packs)'))
                ->helperText(__('Only for packaged goods: how many base units one purchase yields, e.g. 1 pack = 24 pcs. Leave blank for kg/g/l/ml.'))
                ->numeric()
                ->live(onBlur: true)
                ->afterStateUpdated(fn (Get $get, Set $set) => self::syncBasePricing($get, $set))
                ->columnSpan(1),
            TextInput::make('base_qty')
                ->label(__('Quantity in base unit'))
                ->disabled()
                ->dehydrated()
                ->numeric()
                ->columnSpan(1),
            TextInput::make('base_unit')
                ->label(__('Base unit'))
                ->disabled()
                ->dehydrated()
                ->columnSpan(1),
            TextInput::make('price_per_base_unit')
                ->label(__('Price per base unit'))
                ->disabled()
                ->dehydrated()
                ->numeric()
                ->prefix(Setting::get('currency', 'IDR'))
                ->columnSpan(1),

            TextInput::make('quantity')
                ->label(__('Stock on hand'))
                ->helperText(__('In the base unit shown above'))
                ->translateLabel()
                ->numeric()
                ->required(),
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(static::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->translateLabel(),
                TextColumn::make('sku')
                    ->searchable()
                    ->translateLabel(),
                TextColumn::make('quantity')
                    ->label(__('Stock on hand'))
                    ->suffix(fn (RawMaterial $record) => ' '.str($record->base_unit)->upper()),
                TextColumn::make('purchase')
                    ->label(__('Purchase'))
                    ->getStateUsing(fn (RawMaterial $record) => sprintf(
                        '%s %s',
                        (float) $record->input_qty,
                        str($record->input_unit)->upper(),
                    )),
                TextColumn::make('harga_total')
                    ->label(__('Total price'))
                    ->money(
                        currency: Setting::get('currency', 'IDR'),
                        locale: Profile::get()->locale
                    ),
                TextColumn::make('base_qty')
                    ->label(__('Base quantity'))
                    ->getStateUsing(fn (RawMaterial $record) => sprintf(
                        '%s %s',
                        (float) $record->base_qty,
                        str($record->base_unit)->upper(),
                    )),
                TextColumn::make('price_per_base_unit')
                    ->label(__('Price per base unit'))
                    ->money(
                        currency: Setting::get('currency', 'IDR'),
                        locale: Profile::get()->locale
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->disabled(fn (RawMaterial $record) => self::isUsedInRecipe($record))
                    ->tooltip(fn (RawMaterial $record) => self::isUsedInRecipe($record)
                        ? __('Used by a product recipe; remove it there first')
                        : null),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function (Tables\Actions\DeleteBulkAction $action, Collection $records) {
                            $used = $records->filter(fn (RawMaterial $record) => self::isUsedInRecipe($record));

                            if ($used->isEmpty()) {
                                return;
                            }

                            Notification::make()
                                ->danger()
                                ->title(__('Some raw materials are used by product recipes'))
                                ->body($used->pluck('name')->implode(', '))
                                ->send();

                            $action->cancel();
                        }),
                ]),
            ]);
    }

    /**
     * A material referenced by a recipe line cannot be deleted: the foreign
     * key would fail and the user would see a raw SQL error.
     */
    private static function isUsedInRecipe(RawMaterial $record): bool
    {
        return ProductionRecipeItem::query()
            ->where('raw_material_id', $record->getKey())
            ->exists();
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
            'index' => Pages\ListRawMaterials::route('/'),
            'create' => Pages\CreateRawMaterial::route('/create'),
            'edit' => Pages\EditRawMaterial::route('/{record}/edit'),
        ];
    }
}
