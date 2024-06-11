<?php

namespace App\Filament\Tenant\Resources\SellingResource\Pages;

use App\Filament\Tenant\Resources\SellingResource;
use Filament\Forms\Form;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ListSellings extends ListRecords
{
    protected static string $resource = SellingResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            SellingResource\Widgets\SellingOverview::class,
        ];
    }

    protected function configureEditAction(Tables\Actions\EditAction $action): void
    {
        $resource = static::getResource();

        $action
            ->authorize(fn (Model $record): bool => $resource::canEdit($record))
            ->form(fn (Form $form): Form => $this->form($form->columns(2)));

        if ($resource::hasPage('view')) {
            $action->url(fn (Model $record): string => $resource::getUrl('view', ['record' => $record]));
        }
    }

    protected function getTableQuery(): ?Builder
    {
        return $this->getResource()::getEloquentQuery()
            ->isPaid();
    }
}
