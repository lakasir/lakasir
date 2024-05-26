<?php

namespace App\Livewire\Forms\Auth;

use App\Services\RegisterTenant;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('livewire.components.layouts.guest')]
class RegisterTenantForm extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Akun pemilik')
                        ->schema([
                            TextInput::make('full_name')
                                ->label('Nama Lengkap')
                                ->string()
                                ->required(),
                            TextInput::make('email')
                                ->label('Email')
                                ->email()
                                ->rules('unique:tenant_users,email')
                                ->required(),
                            TextInput::make('password')
                                ->password()
                                ->required()
                                ->rules(['confirmed', Password::defaults()]),
                            TextInput::make('password_confirmation')
                                ->password(),
                        ])
                        ->columns(1)
                        ->icon('heroicon-o-user'),
                    Wizard\Step::make('Detail Toko')
                        ->schema([
                            TextInput::make('shop_name')
                                ->label('Nama Toko')
                                ->string()
                                ->required(),
                            TextInput::make('shop_location')
                                ->label('Alamat Toko')
                                ->string(),
                            Select::make('business_type')
                                ->label('Jenis Bisnis')
                                ->options([
                                    'retail' => 'Retail',
                                    'wholesale' => 'Wholesale',
                                    'fnb' => 'F&B',
                                    'fashion' => 'Fashion',
                                    'pharmacy' => 'Pharmacy',
                                    'other' => 'Other',
                                ])
                                ->live()
                                ->required(),
                            TextInput::make('other_business_type')
                                ->label('Lainnya')
                                ->visible(fn (Get $get): bool => $get('business_type') == 'other')
                                ->required(fn (Get $get): bool => $get('business_type') == 'other')
                                ->string(),
                        ])
                        ->icon('heroicon-o-shopping-bag'),
                    Wizard\Step::make('Domain Toko')
                        ->schema([
                            TextInput::make('domain')
                                ->label('Domain')
                                ->rules(['unique:tenants,id'])
                                ->suffix('.'.config('tenancy.central_domains')[0]),
                        ])
                        ->icon('heroicon-o-globe-alt'),
                ])
                    ->submitAction(new HtmlString(
                        Blade::render(<<<'BLADE'
                                    <x-filament::button
                                        type="submit"
                                        size="sm"
                                        wire:click="create"
                                    >
                                        Submit
                                    </x-filament::button>
                                  BLADE))),
            ])
            ->statePath('data');
    }

    public function create(RegisterTenant $registerTenant): void
    {
        $data = $this->form->getState();
        $data = array_merge($data, [
            'name' => strtolower($data['domain']),
            'domain' => strtolower($data['domain'].'.'.config('tenancy.central_domains')[0]),
        ]);

        $tenant = $registerTenant->create($data);
        $securedDomain = 'https://'.$tenant->domains->first()->domain;

        redirect()->to($securedDomain, secure: true);
    }

    public function render()
    {
        return view('livewire.forms.auth.register');
    }
}
