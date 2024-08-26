<?php

namespace App\Filament\Tenant\Pages;

use App\Filament\Tenant\Resources\Traits\RefreshThePage;
use App\Models\Tenants\About;
use App\Models\Tenants\Setting;
use App\Services\Tenants\AboutService;
use App\Traits\HasTranslatableResource;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
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

    public $about = [];

    public $setting = [];

    public function mount(): void
    {
        $about = About::first()?->toArray() ?? $this->about;
        $about['preview_image'] = $about['photo'];
        if ($about['photo']) {
            $about['photo'] = [$about['photo']];
        }
        foreach (config('setting.key') as $key) {
            $this->setting[$key] = Setting::get($key);
        }

        $this->about = $about;
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
}
