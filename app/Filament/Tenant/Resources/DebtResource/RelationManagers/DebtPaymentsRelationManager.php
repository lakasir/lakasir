<?php

namespace App\Filament\Tenant\Resources\DebtResource\RelationManagers;

use App\Filament\Tenant\Resources\DebtResource\Traits\HasDebtPaymentForm;
use App\Filament\Tenant\Resources\Traits\RefreshThePage;
use App\Models\Tenants\Debt;
use App\Models\Tenants\DebtPayment;
use App\Models\Tenants\Setting;
use App\Services\Tenants\DebtPaymentService;
use Filament\Facades\Filament;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class DebtPaymentsRelationManager extends RelationManager
{
    use HasDebtPaymentForm, RefreshThePage;

    protected static string $relationship = 'debtPayments';

    protected static bool $isLazy = false;

    private $dPService;

    public function __construct()
    {
        $this->dPService = new DebtPaymentService();
    }

    public static function canViewForRecord(Model|Debt $ownerRecord, string $pageClass): bool
    {
        return Filament::auth()->user()->can('read debt payment');
    }

    public function form(Form $form): Form
    {
        return $form->schema($this->getFormPayment())->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('amount')
                    ->money(Setting::get('currency', 'IDR')),
                Tables\Columns\TextColumn::make('paymentMethod.name'),
                Tables\Columns\TextColumn::make('last_debt')
                    ->money(Setting::get('currency', 'IDR')),
                Tables\Columns\TextColumn::make('date')
                    ->date(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->action(function (array $data, DebtPayment $debtPayment): void {
                        $this->dPService->update($debtPayment, $data);
                        $this->refreshPage();
                    }),
                Tables\Actions\DeleteAction::make()
                    ->action(function (DebtPayment $debtPayment): void {
                        $this->dPService->destroy($debtPayment);
                        $this->refreshPage();
                    }),
            ]);
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
