<?php

namespace Modules\Warehouse\Filament\Resources\ProductionRecipeResource\RelationManagers;

use App\Filament\Tenant\Components\PriceInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
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
                }),
            TextInput::make('quantity_required')
                ->suffix(function (Get $get) {
                    $unit = RawMaterial::query()
                        ->find($get('raw_material_id'));

                    return str($unit?->unit)?->upper();
                })
                ->live()
                ->numeric()
                ->afterStateUpdated(function ($get, $set, $state) {
                    $rawMaterial = RawMaterial::find($get('raw_material_id'));
                    $costPerRaw = $rawMaterial->price_per_unit * $state;

                    $set('price_per_need', $costPerRaw);
                })
                ->required(),
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
                    ->suffix(fn ($record) => ' '.str($record->rawMaterial->unit)->upper()),
                Tables\Columns\TextColumn::make('price_per_need')
                    ->getStateUsing(fn (ProductionRecipeItem $record) => price_format($record->rawMaterial->price_per_unit * $record->quantity_required))
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
