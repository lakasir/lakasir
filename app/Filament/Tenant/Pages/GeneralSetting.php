<?php

namespace App\Filament\Tenant\Pages;

use App\Filament\Tenant\Resources\Traits\RefreshThePage;
use App\Models\Tenants\About;
use App\Models\Tenants\Profile;
use App\Models\Tenants\Setting;
use App\Models\Tenants\UploadedFile;
use App\Models\Tenants\User;
use App\Services\Tenants\AboutService;
use App\Traits\HasTranslatableResource;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Lakasir\LakasirModule\Facades\LakasirModule;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class GeneralSetting extends Page implements HasActions, HasForms
{
    use HasTranslatableResource,
        InteractsWithFormActions,
        InteractsWithForms,
        RefreshThePage;

    public static ?string $label = 'General Setting';

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $view = 'filament.tenant.pages.general-setting';

    public $formData = [];

    public function mount(): void
    {
        $about = About::first()?->toArray() ?? $this->about;
        $about['preview_image'] = $about['photo'];
        if ($about['photo']) {
            $about['photo'] = [$about['photo']];
        }
        foreach (config('setting.key') as $key) {
            $this->formData['setting'][$key] = Setting::get($key);
        }

        $this->formData['about'] = $about;

        /** @var User $user */
        $user = auth()->user();
        $profile = $user->profile;

        $this->formData['profile'] = [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $profile->phone,
            'address' => $profile->address,
            'locale' => $profile->locale,
            'timezone' => $profile->timezone,
            'photo' => $profile->photo ? [$profile->photo] : null,
        ];

        $this->formData['module'] = [];
    }

    public function form(Form $form): Form
    {
        $tabs = [
            Tabs\Tab::make('About')
                ->statePath('formData.about')
                ->translateLabel()
                ->schema(About::form()),
            Tabs\Tab::make('App')
                ->statePath('formData.setting')
                ->translateLabel()
                ->schema([
                    Select::make('minimum_stock_nofication')
                        ->options([
                            0 => 0,
                            5 => 5,
                            10 => 10,
                            20 => 20,
                            50 => 50,
                        ])
                        ->translateLabel(),
                    TextInput::make('default_tax')
                        ->numeric()
                        ->suffix('%')
                        ->translateLabel(),
                    Actions::make([
                        Action::make('Save')
                            ->translateLabel()
                            ->requiresConfirmation()
                            ->action('saveApp'),
                    ]),
                ]),
            Tabs\Tab::make('Profile')
                ->statePath('formData.profile')
                ->translateLabel()
                ->schema(Profile::form()),
        ];

        if (module_plugin_exist()) {
            array_push($tabs,
                Tabs\Tab::make('Module')
                    ->statePath('formData.module')
                    ->translateLabel()
                    ->schema($this->moduleForm()),
            );
        }

        return $form->schema([
            Tabs::make('Tabs')
                ->tabs($tabs),
        ]);
    }

    public function saveApp(): void
    {
        foreach ($this->formData['setting'] as $key => $value) {
            Setting::set($key, $value);
        }

        Notification::make()
            ->title(__('Success'))
            ->success()
            ->send();

        $this->mount();
    }

    public function saveAbout(AboutService $aboutService): void
    {
        $this->validate([
            'formData.about.shop_name' => 'required',
            'formData.about.shop_location' => 'required',
            'formData.about.currency' => 'required',
            // 'data.photo' => 'required',
        ]);

        if (isset($this->formData['about']['photo']) && $this->formData['about']['photo'] != null && array_values($this->formData['about']['photo'])[0] instanceof TemporaryUploadedFile) {
            /** @var TemporaryUploadedFile $image */
            $image = array_values($this->formData['about']['photo'])[0];
            $image->storePubliclyAs('public', $image->getFilename());
            $url = optional(Storage::disk('public'))->url($image->getFilename());
            $this->formData['about']['photo_url'] = $url;
            $this->formData['about']['photo'] = null;
        }
        $aboutService->createOrUpdate($this->formData['about']);

        Notification::make()
            ->title(__('Success'))
            ->success()
            ->send();

        $this->mount();
    }

    public function saveProfile(): void
    {
        $this->validate([
            'formData.profile.email' => 'required|email',
            'formData.profile.timezone' => 'required',
            'formData.profile.locale' => 'required',
            'formData.profile.password' => 'nullable|confirmed',
            // 'data.photo' => 'required',
        ]);

        /** @var User $user */
        $user = auth()->user();
        $profile = $user->profile;

        if (feature('edit-profile')) {
            if (isset($this->formData['profile']['photo']) && $this->formData['profile']['photo'] != null && array_values($this->formData['profile']['photo'])[0] instanceof TemporaryUploadedFile) {
                /** @var TemporaryUploadedFile $image */
                $image = array_values($this->formData['profile']['photo'])[0];
                $image->storePubliclyAs('public', $image->getFilename());
                $url = optional(Storage::disk('public'))->url($image->getFilename());
                $this->formData['profile']['photo_url'] = $url;
                $this->formData['profile']['photo'] = null;
            }
        }

        if (isset($this->formData['profile']['password']) && $this->formData['profile']['password'] != '') {
            $this->formData['profile']['password'] = bcrypt($this->formData['profile']['password']);
        }

        $user->update($this->formData['profile']);
        $profile->update($this->formData['profile']);

        if (feature('edit-profile')) {
            $data = $this->formData['profile'];

            if (isset($data['photo_url']) && $data['photo_url'] !== $profile->photo) {
                /** @var \App\Models\Tenants\UploadedFile $tmpFile */
                $tmpFile = UploadedFile::where('url', $data['photo_url'])->first();
                $url = $data['photo_url'];
                if ($tmpFile) {
                    $url = $tmpFile->moveToPuplic('profile', $profile->photo ? Str::of($profile->photo)->after('profile/') : null);
                }
                $profile->update([
                    'photo' => $url,
                ]);
            }

            if (! isset($data['photo_url'])) {
                /** @var \App\Models\Tenants\UploadedFile $tmpFile */
                $tmpFile = UploadedFile::where('url', $profile->photo)->first();
                if ($tmpFile) {
                    $tmpFile->deleteFromPublic('');
                } else {
                    $path = parse_url($profile->photo, PHP_URL_PATH); // Get the path from the URL
                    $path = str(ltrim($path, '/'))->remove('storage');
                    $exists = optional(Storage::disk('public'))->has($path);
                    if ($exists) {
                        Storage::disk('public')->delete($path);
                    }
                }

                $profile->update([
                    'photo' => null,
                ]);
            }

        }

        Notification::make()
            ->title(__('Success'))
            ->success()
            ->send();

        $this->mount();
    }

    public function moduleForm(): array
    {
        return [
            FileUpload::make('plugin')
                ->rules(['mimes:zip'])
                ->required(),
            Actions::make([
                Action::make('Install module')
                    ->translateLabel()
                    ->action(function () {
                        $this->validate([
                            'formData.module.plugin' => 'required',
                        ]);

                        foreach ($this->formData['module']['plugin'] as $plugin) {
                            LakasirModule::unzipThePlugin($plugin);
                        }
                    }),
            ]),
        ];
    }
}
