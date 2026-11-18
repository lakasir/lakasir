<?php

namespace App\Filament\Tenant\Resources;

use App\Filament\Tenant\Resources\PaymentMethodResource\Pages;
use App\Models\Tenants\PaymentMethod;
use App\Traits\HasTranslatableResource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PaymentMethodResource extends Resource
{
    use HasTranslatableResource;

    protected static ?string $model = PaymentMethod::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->translateLabel()
                    ->columnSpanFull(),
                Card::make([
                    Checkbox::make('is_cash')->inline(),
                    Checkbox::make('is_debit')->inline(),
                    Checkbox::make('is_credit')->inline(),
                    Checkbox::make('is_wallet')->inline(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->translateLabel()
                    ->searchable(),
                TextColumn::make('is_cash')
                    ->badge()
                    ->getStateUsing(function (PaymentMethod $pMethod) {
                        return $pMethod->is_cash ? 'Yes' : 'No';
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'No' => 'danger',
                        'Yes' => 'success',
                    }),
                TextColumn::make('is_debit')
                    ->badge()
                    ->getStateUsing(function (PaymentMethod $pMethod) {
                        return $pMethod->is_debit ? 'Yes' : 'No';
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'No' => 'danger',
                        'Yes' => 'success',
                    }),
                TextColumn::make('is_credit')
                    ->badge()
                    ->getStateUsing(function (PaymentMethod $pMethod) {
                        return $pMethod->is_credit ? 'Yes' : 'No';
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'No' => 'danger',
                        'Yes' => 'success',
                    }),
                TextColumn::make('is_wallet')
                    ->badge()
                    ->getStateUsing(function (PaymentMethod $pMethod) {
                        return $pMethod->is_wallet ? 'Yes' : 'No';
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'No' => 'danger',
                        'Yes' => 'success',
                    }),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
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
            'index' => Pages\ListPaymentMethods::route('/'),
            'create' => Pages\CreatePaymentMethod::route('/create'),
            'edit' => Pages\EditPaymentMethod::route('/{record}/edit'),
        ];
    }
}
