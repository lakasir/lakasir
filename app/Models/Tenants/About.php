<?php

namespace App\Models\Tenants;

use App\Filament\Tenant\Resources\Traits\HasUploadFileField;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperAbout
 */
class About extends Model
{
    use HasFactory,
        HasUploadFileField;

    protected $guarded = ['id'];

    public static function form(): array
    {
        return [
            TextInput::make('shop_name')
                ->required()
                ->translateLabel(),
            Select::make('business_type')
                ->translateLabel()
                ->options([
                    'retail' => __('Retail'),
                    'wholesale' => __('Wholesale'),
                    'fnb' => __('F&B'),
                    'fashion' => __('Fashion'),
                    'pharmacy' => __('Pharmacy'),
                    'other' => __('Other'),
                ])
                ->live()
                ->required(),
            TextInput::make('other_business_type')
                ->label('Lainnya')
                ->visible(fn (Get $get): bool => $get('business_type') == 'other')
                ->required(fn (Get $get): bool => $get('business_type') == 'other')
                ->string(),
            Textarea::make('shop_location')
                ->required()
                ->translateLabel(),
            FileUpload::make('photo')
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
            Actions::make([
                Action::make('Save')
                    ->translateLabel()
                    ->requiresConfirmation()
                    ->action('saveAbout'),
            ]),
        ];
    }
}
