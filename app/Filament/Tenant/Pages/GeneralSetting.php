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
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Section;
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
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Laravel\Pennant\Feature;

class GeneralSetting extends Page implements HasActions, HasForms
{
    use HasTranslatableResource,
        InteractsWithFormActions,
        InteractsWithForms,
        RefreshThePage;

    public static ?string $label = 'General Setting';

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $view = 'filament.tenant.pages.general-setting';

    public $about = [
        'shop_location' => '',
        'photo' => '',
    ];

    public $setting = [];

    public $feature = [];

    public $profile = [];

    public function mount(): void
    {
        $about = About::first()?->toArray() ?? $this->about;
        if ($about) {
            $about['preview_image'] = $about['photo'];
            if ($about['photo']) {
                $about['photo'] = [$about['photo']];
            }
            foreach (config('setting.key') as $key) {
                $this->setting[$key] = Setting::get($key);
            }
            $this->about = $about;
        }

        $this->feature = [
            'supplier' => Feature::active('supplier'),
            'purchasing' => Feature::active('purchasing'),
            'receivable' => Feature::active('receivable'),
            'stock-opname' => Feature::active('stock-opname'),
            'voucher' => Feature::active('voucher'),
            'pos-v2' => Feature::active('pos-v2'),
            'product-import' => Feature::active('product-import')
        ];

        /** @var User $user */
        $user = auth()->user();
        $profile = $user->profile;

        $this->profile = [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $profile->phone,
            'address' => $profile->address,
            'locale' => $profile->locale,
            'timezone' => $profile->timezone,
            'photo' => $profile->photo ? [$profile->photo] : null,
        ];
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Tabs::make('Tabs')
                ->tabs([
                    Tabs\Tab::make('About')
                        ->statePath('about')
                        ->translateLabel()
                        ->schema(About::form()),
                    Tabs\Tab::make('App')
                        ->statePath('setting')
                        ->translateLabel()
                        ->schema([
                            Select::make('currency')
                                ->options([
                                    'IDR' => 'IDR',
                                    'USD' => 'USD',
                                ])
                                ->translateLabel(),
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
                    Tabs\Tab::make('Feature')
                        ->statePath('feature')
                        ->visible(can('access feature flag'))
                        ->translateLabel()
                        ->schema([
                            Section::make([
                                Checkbox::make('supplier')->inline(),
                                Checkbox::make('purchasing')->inline(),
                                Checkbox::make('receivable')->inline(),
                                Checkbox::make('stock-opname')->inline(),
                                Checkbox::make('voucher')->inline(),
                                Checkbox::make('pos-v2')->label("POS V2")->inline(),
                                Checkbox::make('product-import')->inline(),
                            ]),
                            Actions::make([
                                Action::make('Save')
                                    ->translateLabel()
                                    ->requiresConfirmation()
                                    ->action('saveFeature'),
                            ]),
                        ]),
                    Tabs\Tab::make('Profile')
                        ->statePath('profile')
                        ->translateLabel()
                        ->schema(Profile::form()),
                ]),
        ]);
    }

    public function saveApp(): void
    {
        foreach ($this->setting as $key => $value) {
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
            'about.shop_name' => 'required',
            'about.shop_location' => 'required',
            'about.currency' => 'required',
            // 'data.photo' => 'required',
        ]);

        if (isset($this->about['photo']) && $this->about['photo'] != null && array_values($this->about['photo'])[0] instanceof TemporaryUploadedFile) {
            /** @var TemporaryUploadedFile $image */
            $image = array_values($this->about['photo'])[0];
            $image->storePubliclyAs('public', $image->getFilename());
            $url = optional(Storage::disk('public'))->url($image->getFilename());
            $this->about['photo_url'] = $url;
            $this->about['photo'] = null;
        }
        $aboutService->createOrUpdate($this->about);

        Notification::make()
            ->title(__('Success'))
            ->success()
            ->send();

        $this->mount();
    }

    public function saveFeature(): void
    {
        if (can('access feature flag')) {
            foreach ($this->feature as $name => $value) {
                if ($value) {
                    Feature::activate($name);
                } else {
                    Feature::deactivate($name);
                }
            }

            Notification::make()
                ->title(__('Success'))
                ->success()
                ->send();

            $this->mount();
        }
    }

    public function saveProfile(): void
    {
        $this->validate([
            'profile.email' => 'required|email',
            'profile.timezone' => 'required',
            'profile.locale' => 'required',
            'profile.password' => 'nullable|confirmed',
            // 'data.photo' => 'required',
        ]);

        /** @var User $user */
        $user = auth()->user();
        $profile = $user->profile;

        if (feature('edit-profile')) {
            if (isset($this->profile['photo']) && $this->profile['photo'] != null && array_values($this->profile['photo'])[0] instanceof TemporaryUploadedFile) {
                /** @var TemporaryUploadedFile $image */
                $image = array_values($this->profile['photo'])[0];
                $image->storePubliclyAs('public', $image->getFilename());
                $url = optional(Storage::disk('public'))->url($image->getFilename());
                $this->profile['photo_url'] = $url;
                $this->profile['photo'] = null;
            }
        }

        if (isset($this->profile['password']) && $this->profile['password'] != '') {
            $this->profile['password'] = bcrypt($this->profile['password']);
        }

        $user->update($this->profile);
        $profile->update($this->profile);

        if (feature('edit-profile')) {
            $data = $this->profile;

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
}
