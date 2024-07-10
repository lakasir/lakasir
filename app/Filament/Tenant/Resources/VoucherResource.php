<?php

namespace App\Filament\Tenant\Resources;

use App\Filament\Tenant\Resources\Traits\RedirectToIndex;
use App\Filament\Tenant\Resources\VoucherResource\Pages;
use App\Models\Tenants\Setting;
use App\Models\Tenants\Voucher;
use App\Traits\HasTranslatableResource;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\IconPosition;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VoucherResource extends Resource
{
    use HasTranslatableResource, RedirectToIndex;

    protected static ?string $model = Voucher::class;

    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->translateLabel()
                    ->required(),
                TextInput::make('code')
                    ->translateLabel()
                    ->unique(ignorable: function (?Voucher $record) {
                        return $record;
                    })
                    ->required(),
                Select::make('type')
                    ->translateLabel()
                    ->options([
                        'percentage' => __('Percentage'),
                        'flat' => __('Flat'),
                    ])
                    ->required(),
                TextInput::make('nominal')
                    ->numeric()
                    ->lte(function (Get $get) {
                        if ($get('voucher_type') == 'percentage') {
                            return 100;
                        }
                    })
                    ->label(__('Nominal'))
                    ->required(),
                TextInput::make('kuota')
                    ->numeric()
                    ->label(__('Kuota'))
                    ->required(),
                DatePicker::make('start_date')
                    ->native(false)
                    ->translateLabel()
                    ->required(),
                DatePicker::make('expired')
                    ->native(false)
                    ->translateLabel()
                    ->gte('start_date')
                    ->required(),
                TextInput::make('minimal_buying')
                    ->translateLabel()
                    ->stripCharacters(',')
                    ->numeric()
                    ->mask(RawJs::make('$money($input)'))
                    ->prefix(Setting::get('currency', 'IDR'))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('code')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('start_date')
                    ->translateLabel()
                    ->date(),
                TextColumn::make('expired')
                    ->icon(fn (mixed $state) => now()->gt($state) ? 'heroicon-s-exclamation-triangle' : '')
                    ->iconColor(fn (mixed $state) => now()->gt($state) ? Color::Red : '')
                    ->iconPosition(IconPosition::After)
                    ->translateLabel()
                    ->date(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListVouchers::route('/'),
            'create' => Pages\CreateVoucher::route('/create'),
            'edit' => Pages\EditVoucher::route('/{record}/edit'),
        ];
    }
}
