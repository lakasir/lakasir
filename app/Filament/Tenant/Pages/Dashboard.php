<?php

namespace App\Filament\Tenant\Pages;

use App\Filament\Tenant\Resources\SellingResource\Widgets\SellingOverview;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Dashboard\Actions\FilterAction;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         FilterAction::make()
    //             ->form([
    //                 DatePicker::make('startDate')
    //                     ->native(false),
    //                 DatePicker::make('endDate')
    //                     ->native(false),
    //             ]),
    //     ];
    // }

    protected function getHeaderWidgets(): array
    {
        return [
            SellingOverview::class,
        ];
    }
}
