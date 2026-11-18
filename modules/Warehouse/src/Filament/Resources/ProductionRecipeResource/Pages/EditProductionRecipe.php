<?php

namespace Modules\Warehouse\Filament\Resources\ProductionRecipeResource\Pages;

use App\Filament\Tenant\Resources\Traits\RedirectToIndex;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;
use Modules\Warehouse\Filament\Resources\ProductionRecipeResource;
use Modules\Warehouse\Models\RawMaterial;

class EditProductionRecipe extends EditRecord
{
    use RedirectToIndex;

    protected static string $resource = ProductionRecipeResource::class;

    public function getTitle(): string|Htmlable
    {
        return __('Add recipe for product', [
            'name' => $this->record->name,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Repeater::make('raw_materials')
                    ->schema([
                        Split::make([
                            Select::make('raw_material_id')
                                ->options(RawMaterial::pluck('name', 'id'))
                                ->live()
                                ->suffix(function (Get $get) {
                                    return RawMaterial::find($get('raw_material_id'))?->unit;
                                })
                                ->searchable(),
                            TextInput::make('quantity_required'),
                        ]),
                    ]),
            ]);
    }
}
