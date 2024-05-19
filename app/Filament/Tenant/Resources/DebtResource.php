<?php

namespace App\Filament\Tenant\Resources;

use App\Filament\Tenant\Resources\DebtResource\Pages;
use App\Filament\Tenant\Resources\DebtResource\RelationManagers\DebtItemsRelationManager;
use App\Filament\Tenant\Resources\DebtResource\RelationManagers\DebtPaymentsRelationManager;
use App\Models\Tenants\Debt;
use App\Models\Tenants\Setting;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DebtResource extends Resource
{
    protected static ?string $model = Debt::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('selling.code')
                    ->searchable()
                    ->prefix('#')
                    ->label(__('Transaction Code')),
                TextColumn::make('member.name')
                    ->searchable()
                    ->label(__('Member Name')),
                TextColumn::make('total_debt')
                    ->money(Setting::get('currency', 'IDR'))
                    ->label(__('Total Debt')),
                TextColumn::make('rest_debt')
                    ->money(Setting::get('currency', 'IDR'))
                    ->label(__('Rest Debt')),
                TextColumn::make('due_date')
                    ->label(__('Due Date'))
                    ->date(),
                TextColumn::make('last_billing_date')
                    ->label(__('Last Billing Date'))
                    ->date(),
                TextColumn::make('status')
                    ->badge()
                    ->getStateUsing(function (Debt $debt) {
                        return $debt->status ? 'Paid off' : 'Unpaid';
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'Unpaid' => 'danger',
                        'Paid off' => 'success',
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationGroup::make('', [
                DebtItemsRelationManager::make(),
                DebtPaymentsRelationManager::make(),
            ]),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDebts::route('/'),
            'view' => Pages\ViewDebt::route('/{record}'),
        ];
    }
}
