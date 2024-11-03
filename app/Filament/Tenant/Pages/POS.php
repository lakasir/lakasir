<?php

namespace App\Filament\Tenant\Pages;

use App\Models\Tenants\Category;
use App\Models\Tenants\Product;
use App\Traits\HasTranslatableResource;
use Filament\Pages\Page;

class POS extends Page
{
    use HasTranslatableResource;

    public static ?string $label = 'POS V2';

    protected static ?string $navigationIcon = 'heroicon-o-bolt';

    protected static string $view = 'filament.tenant.pages.pos';

    protected static string $layout = 'filament-panels::components.layout.base';

    public $menuItems = [];

    public $categories = [];

    public function mount()
    {
        $this->menuItems = Product::query()
            ->limit(20)
            ->get();

        $this->categories = array_merge([
            [
                'id' => 'all',
                'name' => 'All',
            ],
        ], Category::query()
            ->get()
            ->toArray());
    }
}
