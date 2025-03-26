<?php

namespace App\Filament\Tenant\Pages;

use App\Filament\Tenant\Pages\Traits\CartInteraction;
use App\Models\Tenants\CartItem;
use App\Models\Tenants\Category;
use App\Models\Tenants\Product;
use App\Traits\HasTranslatableResource;
use Filament\Pages\Page;

class POS extends Page
{
    use CartInteraction, HasTranslatableResource;

    public static ?string $label = 'POS V2';

    protected static ?string $navigationIcon = 'heroicon-o-bolt';

    protected static string $view = 'filament.tenant.pages.pos.index';

    protected static string $layout = 'filament-panels::components.layout.base';

    public $menuItems = [];

    public $categories = [];

    public $cartItems = [];

    public function mount()
    {
        $this->allCategory();

        $this->categories = array_merge([
            [
                'id' => 'all',
                'name' => 'All',
            ],
        ], Category::query()
            ->get()
            ->toArray());

        $this->refreshCart();
    }

    public function refreshCart()
    {
        $this->cartItems = CartItem::get()->toArray();
    }

    public function addToCart(Product $product, ?array $data = null): void
    {
        $this->addCart($product, $data);
        $this->dispatch('refreshPage', [
            'cartItems' => $this->cartItems,
            'categories' => $this->categories,
            'menuItems' => $this->menuItems,
        ]);
    }

    public function allCategory(): void
    {
        $this->menuItems = Product::query()
            ->limit(20)
            ->get();

        $this->dispatch('refreshPage', [
            'cartItems' => $this->cartItems,
            'categories' => $this->categories,
            'menuItems' => $this->menuItems,
        ]);
    }

    public function filterCategoryId(?Category $category): void
    {
        $this->menuItems = Product::whereCategoryId($category->id)->get();

        $this->dispatch('refreshPage', [
            'cartItems' => $this->cartItems,
            'categories' => $this->categories,
            'menuItems' => $this->menuItems,
        ]);
    }

    public function getFilteredMenuItemsProperty()
    {
        return collect($this->menuItems)
            ->when($this->search, function ($collection) {
                return $collection->filter(function ($item) {
                    return str_contains(strtolower($item['name']), strtolower($this->search));
                });
            })
            ->when($this->category !== 'All', function ($collection) {
                return $collection->where('category_id', $this->category);
            });
    }

    public function scanProduct(string $barcode): void
    {
        $this->addCartUsingScanner($barcode);

        $this->dispatch('refreshPage', [
            'cartItems' => $this->cartItems,
            'categories' => $this->categories,
            'menuItems' => $this->menuItems,
        ]);
    }
}
