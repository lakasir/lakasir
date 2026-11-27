<?php

namespace App\Filament\Tenant\Resources;

use App\Filament\Tenant\Resources\CompanyResource\Pages;
use App\Models\Company;
use App\Services\ProvisionCompany;
use App\Traits\HasTranslatableResource;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class CompanyResource extends Resource
{
    use HasTranslatableResource;

    protected static ?string $model = Company::class;

    protected static ?string $title = 'Companies';

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $navigationGroup = 'Setting';

    public static function canAccess(): bool
    {
        return can('access general setting');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')
                ->required()
                ->maxLength(255),
            TextInput::make('domain')
                ->helperText(__('Optional. The POS app signs in by domain.'))
                ->maxLength(255),
            Checkbox::make('enabled')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function(Builder $query) {
                return $query->hasUser();
            })
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('domain')
                    ->placeholder('-'),
                IconColumn::make('enabled')
                    ->boolean(),
                TextColumn::make('trial_ends_at')
                    ->dateTime()
                    ->placeholder(__('No trial')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    /**
     * A shop is created with its own payment methods, permissions and default
     * category, then the acting user is attached as its admin.
     */
    public static function createWithOwner(array $data, ProvisionCompany $provision): Company
    {
        $company = $provision->create($data + [
            'owner' => auth()->user(),
            'provisioned_by' => 'local',
        ]);

        Notification::make()
            ->title(__('Shop created'))
            ->success()
            ->send();

        return $company;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompany::route('/create'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
        ];
    }
}
