<?php

namespace App\Filament\Tenant\Resources;

use App\Filament\Tenant\Resources\DebtResource\Pages;
use App\Filament\Tenant\Resources\DebtResource\RelationManagers\DebtItemsRelationManager;
use App\Filament\Tenant\Resources\DebtResource\RelationManagers\DebtPaymentsRelationManager;
use App\Filament\Tenant\Resources\DebtResource\Traits\HasDebtPaymentForm;
use App\Models\Tenants\Debt;
use App\Models\Tenants\DebtPayment;
use App\Models\Tenants\Setting;
use App\Services\Tenants\DebtPaymentService;
use App\Traits\HasTranslatableResource;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DebtResource extends Resource
{
    use HasDebtPaymentForm, HasTranslatableResource;

    protected static ?string $model = Debt::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    public static function table(Table $table): Table
    {
        $self = new self();

        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('selling.code')
                    ->translateLabel()
                    ->searchable()
                    ->prefix('#'),
                TextColumn::make('member.name')
                    ->translateLabel()
                    ->searchable(),
                TextColumn::make('total_debt')
                    ->money(Setting::get('currency', 'IDR'))
                    ->translateLabel(),
                TextColumn::make('rest_debt')
                    ->money(Setting::get('currency', 'IDR'))
                    ->translateLabel(),
                TextColumn::make('due_date')
                    ->translateLabel()
                    ->date(),
                TextColumn::make('last_billing_date')
                    ->translateLabel()
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
                Action::make('add_payment')
                    ->translateLabel()
                    ->icon('heroicon-s-credit-card')
                    ->model(DebtPayment::class)
                    ->visible(function ($record) {
                        if (! $record->status && can('create debt payment')) {
                            return true;
                        }

                        return false;
                    })
                    ->form(fn ($record) => $self->getFormPayment($record))
                    ->action(function (array $data, Debt $debt, DebtPaymentService $dpService): void {
                        $dpService->create($debt, $data);
                    }),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            TextEntry::make('member.name')
                ->translateLabel(),
            TextEntry::make('member.email')
                ->translateLabel(),
            TextEntry::make('total_debt')
                ->translateLabel()
                ->money(Setting::get('currency', 'IDR')),
            TextEntry::make('rest_debt')
                ->translateLabel()
                ->money(Setting::get('currency', 'IDR')),
            TextEntry::make('due_date')
                ->translateLabel()
                ->date(),
            TextEntry::make('total_billing_via_whatsapp')
                ->translateLabel(),
            TextEntry::make('last_billing_date')
                ->translateLabel()
                ->date(),
            TextEntry::make('status')
                ->translateLabel()
                ->getStateUsing(function (Debt $debt) {
                    return $debt->status ? 'Paid off' : 'Unpaid';
                })
                ->badge()
                ->iconColor(fn (string $state): string => match ($state) {
                    'Unpaid' => 'danger',
                    'Paid off' => 'success',
                })
                ->color(fn (string $state): string => match ($state) {
                    'Unpaid' => 'danger',
                    'Paid off' => 'success',
                })
                ->icon(fn (string $state): string => match ($state) {
                    'Paid off' => 'heroicon-o-check-circle',
                    'Unpaid' => 'heroicon-o-exclamation-circle',
                }),
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
