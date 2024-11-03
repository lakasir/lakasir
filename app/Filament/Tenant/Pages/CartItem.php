<?php

namespace App\Filament\Tenant\Pages;

use Filament\Pages\Page;

class CartItem extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.tenant.pages.cart-item';

    protected static string $layout = 'filament-panels::components.layout.base';
}
