<?php

namespace App\Filament\Tenant\Resources\ProductResource\Pages;

use App\Filament\Tenant\Resources\ProductResource;
use App\Filament\Tenant\Resources\Traits\RedirectToIndex;
use App\Models\Tenants\Product;
use App\Models\Tenants\UploadedFile;
use App\Services\Tenants\ProductService;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Lakasir\LakasirModule\Enums\Form;
use Lakasir\LakasirModule\Forms\ExtendModuleForm;

class EditProduct extends EditRecord
{
    use RedirectToIndex;

    protected static string $resource = ProductResource::class;

    private ProductService $productService;

    public function __construct()
    {
        $this->productService = new ProductService();
    }

    protected function getCancelFormAction(): Action
    {
        return Action::make('cancel')
            ->label(__('filament-panels::resources/pages/edit-record.form.actions.cancel.label'))
            ->url(static::getResource()::getUrl())
            ->color('gray');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data = $this->getRecord()->attributesToArray();
        $uploadedFile = UploadedFile::inUrl($data['hero_images'])
            ->select(['name', 'original_name'])
            ->get();
        $uploadedFile->each(function ($file, $key) use (&$data) {
            $data['hero_images'][$key] = '/product/'.$file->name;
            $data['original_name'][$data['hero_images'][$key]] = $file->original_name;
        });

        $data = ExtendModuleForm::fill($data, Form::PRODUCT, $this->record);

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        /** @var Product $product */
        $product = $this->getRecord();
        $urls = Arr::map($data['hero_images'], function ($heroImage) {
            return optional(Storage::disk('public'))->url($heroImage);
        });

        if (! $product->hero_images) {
            $product->hero_images = collect([]);
        }
        $deletedHeroImages = $product->hero_images->diff($urls);

        $this->productService->handleDeleteUploadedFile($deletedHeroImages->toArray());

        $model = parent::handleRecordUpdate($record, $data);

        return $model;
    }

    public function afterSave()
    {
        /** @var Product $product */
        $product = $this->record;
        $product->hero_images = $this->productService
            ->handleCreateUploadedFile(
                $this->form->getState()['original_name']
            );

        $product->save();

        if (module_plugin_exist()) {
            \Lakasir\LakasirModule\Events\ProductUpdated::dispatch($product, $this->data);
        }
    }
}
