<?php

namespace App\Livewire\Forms\Global;

use App\Models\Tenants\User;
use Filament\Facades\Filament;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class LocalizationSelector extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public function mount()
    {
        /** @var User $user */
        $user = Filament::auth()->user();
        $this->form->fill([
            'locale' => $user->profile?->locale ?? 'en',
            'currentRoute' => Route::currentRouteName(),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('locale')
                    ->hiddenLabel()
                    ->selectablePlaceholder(false)
                    ->options([
                        'id' => 'Bahasa Indonesia',
                        'en' => 'English',
                    ])
                    ->extraInputAttributes(['wire:change' => 'submit']),
                Hidden::make('currentRoute'),
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $data = $this->form->getState();
        /** @var User $user */
        $user = Filament::auth()->user();
        $user->profile()->updateOrCreate(
            [
                'user_id' => $user->getKey(),
            ],
            [
                'locale' => $data['locale'],
            ]
        );

        $this->redirectRoute($data['currentRoute']);
    }

    public function render(): View
    {
        return view('livewire.forms.global.localization-selector');
    }
}
