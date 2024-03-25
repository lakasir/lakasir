<?php

namespace App\Filament\Tenant\Pages;

use App\Models\Tenants\User;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\EditProfile as AuthEditProfile;
use Illuminate\Database\Eloquent\Model;

class EditProfile extends AuthEditProfile
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected function getRedirectUrl(): ?string
    {
        return '/member';
    }

    protected function getPhoneFormComponent(): Component
    {
        return TextInput::make('phone')
            ->label('Phone')
            ->tel()
            ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/');

    }

    protected function getAddressesFormComponent(): Component
    {
        return Textarea::make('address')
            ->label('Addresses');
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordUpdate(Model|User $record, array $data): Model
    {
        $record->update($data);
        $record->profile()->update([
            'phone' => $data['phone'],
            'address' => $data['address'],
        ]);

        return $record;
    }

    protected function fillForm(): void
    {
        $data = $this->getUser()->with('profile')->first()->toArray();
        $data = array_merge($data, $data['profile']);

        $this->callHook('beforeFill');

        $data = $this->mutateFormDataBeforeFill($data);

        $this->form->fill($data);

        $this->callHook('afterFill');
    }

    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        $this->getPhoneFormComponent(),
                        $this->getAddressesFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                    ])
                    ->operation('edit')
                    ->model($this->getUser())
                    ->statePath('data'),
            ),
        ];
    }
}
