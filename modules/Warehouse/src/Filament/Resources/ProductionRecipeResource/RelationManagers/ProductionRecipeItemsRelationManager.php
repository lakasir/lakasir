<?php

namespace Modules\Warehouse\Filament\Resources\ProductionRecipeResource\RelationManagers;

use App\Filament\Tenant\Components\PriceInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Modules\Warehouse\Filament\Resources\RawMaterialResource;
use Modules\Warehouse\Models\ProductionRecipeItem;
use Modules\Warehouse\Models\RawMaterial;

class ProductionRecipeItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'productionRecipeItems';

    public static function getDefaultForm(): array
    {
        return [
            Select::make('raw_material_id')
                ->live(debounce: 500)
                ->searchable()
                ->options(RawMaterial::pluck('name', 'id'))
                ->createOptionForm(RawMaterialResource::getForm())
                ->createOptionUsing(function (array $data) {
                    return RawMaterial::create($data)->id;
                })
                ->afterStateUpdated(function ($state, Get $get, Set $set) {
                    // The unit list depends on the chosen material, so reset the
                    // unit to that material's own and reprice with it.
                    $rawMaterial = RawMaterial::find($state);

                    if (! $rawMaterial) {
                        $set('quantity_unit', null);
                        $set('price_per_need', null);

                        return;
                    }

                    $set('quantity_unit', $rawMaterial->base_unit);
                    $set(
                        'price_per_need',
                        $rawMaterial->costFor((float) $get('quantity_required'), $rawMaterial->base_unit),
                    );
                }),
            TextInput::make('quantity_required')
                ->suffix(function (Get $get) {
                    $unit = RawMaterial::query()
                        ->find($get('raw_material_id'));

                    return str($get('quantity_unit') ?? $unit?->base_unit)?->upper();
                })
                ->live()
                ->numeric()
                ->afterStateUpdated(function ($get, $set, $state) {
                    $rawMaterial = RawMaterial::find($get('raw_material_id'));

                    if (! $rawMaterial) {
                        return;
                    }

                    $set('price_per_need', $rawMaterial->costFor(
                        (float) $state,
                        $get('quantity_unit') ?? $rawMaterial->base_unit,
                    ));
                })
                ->required(),
            Select::make('quantity_unit')
                ->label(__('Unit'))
                ->options(fn (Get $get) => RawMaterial::unitOptions($get('raw_material_id')))
                ->default(function (Get $get) {
                    return RawMaterial::find($get('raw_material_id'))?->base_unit;
                })
                ->live()
                ->afterStateUpdated(function ($get, $set, $state) {
                    $rawMaterial = RawMaterial::find($get('raw_material_id'));

                    if (! $rawMaterial) {
                        return;
                    }

                    $set('price_per_need', $rawMaterial->costFor(
                        (float) $get('quantity_required'),
                        $state,
                    ));
                }),
            PriceInput::make('price_per_need')
                ->readOnly(),

        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Split::make(static::getDefaultForm()),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('rawMaterial.name')
                    ->label(__('Warehouse::app.production_recipe.raw_material')),
                Tables\Columns\TextColumn::make('quantity_required')
                    ->label(__('Warehouse::app.production_recipe.quantity_required'))
                    ->suffix(fn ($record) => ' '.str($record->quantity_unit ?? $record->rawMaterial?->base_unit)->upper()),
                Tables\Columns\TextColumn::make('price_per_need')
                    ->getStateUsing(fn (ProductionRecipeItem $record) => price_format(
                        $record->rawMaterial?->costFor(
                            (float) $record->quantity_required,
                            $record->quantity_unit ?? $record->rawMaterial->base_unit,
                        ) ?? 0
                    ))
                    ->label(__('Warehouse::app.production_recipe.price_per_need')),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Warehouse::app.production_recipe.title');
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
