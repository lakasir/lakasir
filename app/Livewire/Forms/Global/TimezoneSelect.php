<?php

namespace App\Livewire\Forms\Global;

use App\Models\Tenants\User;
use Filament\Facades\Filament;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Tapp\FilamentTimezoneField\Forms\Components\TimezoneSelect as ComponentsTimezoneSelect;

class TimezoneSelect extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public function mount()
    {
        /** @var User $user */
        $user = Filament::auth()->user();
        $this->form->fill([
            'timezone' => $user->profile?->timezone ?? 'UTC',
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                ComponentsTimezoneSelect::make('timezone')
                    ->hiddenLabel()
                    ->searchable()
                    ->extraInputAttributes(['wire:change' => 'submit']),
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
                'timezone' => $data['timezone'],
            ]
        );
    }

    public function render(): View
    {
        return view('livewire.forms.global.timezone-select');
    }
}
