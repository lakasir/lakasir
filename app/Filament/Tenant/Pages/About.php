<?php

namespace App\Filament\Tenant\Pages;

use App\Models\Tenants\About as TenantsAbout;
use Filament\Actions\Action;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;

class About extends Page implements HasActions, HasForms
{
    use InteractsWithFormActions, InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static string $view = 'filament.tenant.pages.about';

    public ?array $data = [
        'shop_name' => null,
        'shop_location' => null,
        'currency' => null,
        'business_type' => null,
        'other_business_type' => null,
        'photo' => null,
    ];

    public function mount(): void
    {
        $this->data = TenantsAbout::first()?->toArray() ?? $this->data;
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('shop_name')
                ->required()
                ->translateLabel(),
            Textarea::make('shop_location')
                ->required()
                ->translateLabel(),
            Select::make('currency')
                ->required()
                ->options([
                    'IDR' => 'IDR',
                ])
                ->translateLabel(),
            // FileUpload::make('photo')
            //     ->translateLabel(),

        ])
            ->statePath('data');
    }

    public function getFormActions(): array
    {
        return [
            Action::make(__('Save'))
                ->action('save'),
        ];
    }

    public function save(): void
    {
        $about = TenantsAbout::first();
        if (! $about) {
            TenantsAbout::create($this->data);
        } else {
            $about->fill($this->data);
            $about->save();
        }

        Notification::make()
            ->title(__('Success'))
            ->success()
            ->send();
    }
}
