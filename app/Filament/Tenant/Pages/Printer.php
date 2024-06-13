<?php

namespace App\Filament\Tenant\Pages;

use App\Traits\HasTranslatableResource;
use Filament\Actions\Action;
use Filament\Actions\Contracts\HasActions;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;

class Printer extends Page implements HasActions
{
    use HasTranslatableResource;
    use InteractsWithFormActions;

    protected static ?string $navigationIcon = 'heroicon-o-printer';

    protected static string $view = 'filament.tenant.pages.printer';

    public function getFormActions(): array
    {
        return [
            Action::make(__('Fetch connected usb'))
                ->icon('heroicon-o-server')
                ->extraAttributes([
                    'x-on:click' => 'fetchTheUsb',
                ]),
        ];
    }
}
