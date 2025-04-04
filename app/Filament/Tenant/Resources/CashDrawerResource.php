<?php

namespace App\Filament\Tenant\Resources;

use App\Filament\Tenant\Resources\CashDrawerResource\Pages;
use App\Models\Tenants\CashDrawer;
use App\Models\Tenants\Setting;
use App\Traits\HasTranslatableResource;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CashDrawerResource extends Resource
{
    use HasTranslatableResource;

    protected static ?string $model = CashDrawer::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('openedBy.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('closedBy.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cash')
                    ->money(Setting::get('currency', 'IDR'))
                    ->searchable(),
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
            'index' => Pages\ListCashDrawers::route('/')
        ];
    }
}
