<?php

namespace App\Models\Tenants;

use App\Filament\Tenant\Resources\Traits\HasUploadFileField;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Tapp\FilamentTimezoneField\Forms\Components\TimezoneSelect;

/**
 * @mixin IdeHelperProfile
 */
class Profile extends Model
{
    use HasFactory,
        HasUploadFileField;

    protected $fillable = [
        'phone',
        'address',
        'locale',
        'photo',
        'timezone',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function get(array $key = ['*']): Profile
    {
        return Profile::select($key)->whereUserId(auth()->id())->first() ?? new Profile();
    }

    public static function form(): array
    {
        return [
            TextInput::make('name')
                ->label(__('filament-panels::pages/auth/edit-profile.form.name.label'))
                ->required()
                ->maxLength(255)
                ->autofocus(),
            TextInput::make('email')
                ->label(__('filament-panels::pages/auth/edit-profile.form.email.label'))
                ->email()
                ->required()
                ->maxLength(255)
                ->unique(ignoreRecord: true),
            TimezoneSelect::make('timezone')
                ->translateLabel()
                ->searchable(),
            Select::make('locale')
                ->label(__('Language'))
                ->selectablePlaceholder(false)
                ->options([
                    'id' => 'Bahasa Indonesia',
                    'en' => 'English',
                ]),
            FileUpload::make('photo')
                ->visible(feature('edit-profile'))
                ->imageResizeMode('cover')
                ->imageCropAspectRatio('1:1')
                ->imageEditor()
                ->image()
                ->getUploadedFileUsing(function ($file, string|array|null $storedFileNames, $component) {
                    $static = new static;

                    $file = str($file)->remove(config('app.url'));

                    return $static->getUploadedFileUsing($component, $file, $storedFileNames);
                })
                ->imageEditorMode(2)
                ->translateLabel(),
            TextInput::make('password')
                ->label(__('filament-panels::pages/auth/edit-profile.form.password.label'))
                ->password()
                ->revealable(filament()->arePasswordsRevealable())
                ->rule(Password::default())
                ->autocomplete('new-password')
                ->dehydrated(fn ($state): bool => filled($state))
                ->dehydrateStateUsing(fn ($state): string => Hash::make($state))
                ->live(debounce: 500)
                ->same('passwordConfirmation'),
            TextInput::make('password_confirmation')
                ->label(__('filament-panels::pages/auth/edit-profile.form.password_confirmation.label'))
                ->password()
                ->revealable(filament()->arePasswordsRevealable())
                ->required()
                ->visible(fn (Get $get): bool => filled($get('password')))
                ->dehydrated(false),
            Actions::make([
                Action::make('Save')
                    ->translateLabel()
                    ->requiresConfirmation()
                    ->action('saveProfile'),
            ]),
        ];
    }
}
