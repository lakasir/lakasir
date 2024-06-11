<?php

namespace App\Filament\Tenant\Resources;

use App\Filament\Tenant\Resources\MemberResource\Pages;
use App\Models\Tenants\Member;
use App\Traits\HasTranslatableResource;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MemberResource extends Resource
{
    use HasTranslatableResource;

    protected static ?string $model = Member::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('identity_type')
                    ->translateLabel()
                    ->options([
                        'sim' => 'Sim',
                        'ktp' => 'Ktp',
                        'other' => __('Other'),
                    ]),
                TextInput::make('identity_number')
                    ->label(__('Identity number'))
                    ->required(),
                TextInput::make('name')
                    ->label(__('Name'))
                    ->required(),
                TextInput::make('code')
                    ->label(__('Code')),
                TextInput::make('address')
                    ->label(__('Address')),
                TextInput::make('email')
                    ->label(__('Contact'))
                    ->placeholder(__('Please provide a valid email address or whatsapp/phone number.')),
                DatePicker::make('joined_date')
                    ->translateLabel(),
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
                    ->label(__('Name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('code')
                    ->label(__('Code'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('address')
                    ->label(__('Address'))
                    ->searchable(),
                TextColumn::make('email')
                    ->label(__('Contact'))
                    ->searchable(),

                TextColumn::make('identity_number')
                    ->label(__('Identity number'))
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
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
            'index' => Pages\ListMembers::route('/'),
            'create' => Pages\CreateMember::route('/create'),
            'edit' => Pages\EditMember::route('/{record}/edit'),
        ];
    }
}
