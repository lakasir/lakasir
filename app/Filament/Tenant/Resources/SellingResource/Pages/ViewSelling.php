<?php

namespace App\Filament\Tenant\Resources\SellingResource\Pages;

use App\Filament\Tenant\Resources\SellingResource;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewSelling extends ViewRecord
{
    protected static string $resource = SellingResource::class;

    public function getTitle(): string|Htmlable
    {
        return 'View '.$this->getRecord()->code;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('print')
                ->icon('heroicon-s-printer')
                ->visible(Filament::auth()->user()->can('can print selling'))
                ->action('printReceipt'),
        ];
    }

    public function printReceipt()
    {
        $this->redirectRoute('selling.print', $this->getRecord());
    }
}
