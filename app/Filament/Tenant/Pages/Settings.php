<?php

namespace App\Filament\Tenant\Pages;

use App\Models\Tenants\Setting;
use App\Traits\HasTranslatableResource;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;

class Settings extends Page implements HasForms
{
    use HasTranslatableResource, InteractsWithFormActions;

    protected static string $routePath = '/member/settings';

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $view = 'filament.tenant.pages.settings';

    public array $setting = [];

    public function mount(): void
    {
        $setting = Setting::pluck('value', 'key')
            ->toArray();

        $this->form->fill([
            'selling_method' => 'fifo',
        ]);

        $this->setting = $setting;
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Select::make('selling_method')
                ->options([
                    'normal' => 'Normal',
                    'fifo' => 'Fifo',
                    'lifo' => 'Lifo',
                ]),
        ])->model(Setting::class)
            ->operation('save')
            ->statePath('setting');
    }

    public function getFormActions(): array
    {
        return [
            Action::make('save')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        Setting::set('selling_method', $this->setting['selling_method']);
    }
}
