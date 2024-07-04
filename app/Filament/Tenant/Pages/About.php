<?php

namespace App\Filament\Tenant\Pages;

use App\Filament\Tenant\Resources\Traits\RefreshThePage;
use App\Forms\Components\ImagePreview;
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
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class About extends Page implements HasActions, HasForms
{
    use InteractsWithFormActions, InteractsWithForms, RefreshThePage;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static string $view = 'filament.tenant.pages.about';

    public ?array $data = [
        'shop_name' => null,
        'shop_location' => null,
        'currency' => null,
        'business_type' => null,
        'other_business_type' => null,
        'photo' => null,
        'preview' => null,
        'images' => null,
    ];

    public function mount(): void
    {
        $data = TenantsAbout::first()?->toArray() ?? $this->data;
        $data['preview_image'] = $data['photo'];

        $this->data = $data;
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
            ImagePreview::make('preview_image')
                ->translateLabel(),
            FileUpload::make('images')
                ->imageResizeMode('cover')
                ->imageCropAspectRatio('1:1')
                ->imageEditor()
                ->image()
                ->imageEditorMode(2)
                ->translateLabel(),
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
        $this->validate([
            'data.shop_name' => 'required',
            'data.shop_location' => 'required',
            'data.currency' => 'required',
            // 'data.images' => 'required',
        ]);

        $about = TenantsAbout::first();
        if ($this->data['images'] != null && array_values($this->data['images'])[0] instanceof TemporaryUploadedFile) {
            /** @var TemporaryUploadedFile $image */
            $image = array_values($this->data['images'])[0];
            $image->storePubliclyAs('public', $image->getFilename());
            $url = optional(Storage::disk('public'))->url($image->getFilename());
            $this->data['photo'] = $url;
        }

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
        $this->mount();
    }
}
