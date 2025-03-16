<?php

namespace App\Filament\Tenant\Pages;

use App\Filament\Tenant\Pages\Traits\CartInteraction;
use App\Models\Tenants\CartItem as TenantsCartItem;
use App\Models\Tenants\Product;
use App\Traits\HasTranslatableResource;
use Filament\Pages\Page;

class CartItem extends Page
{
    use CartInteraction, HasTranslatableResource;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.tenant.pages.pos.cart-item';

    protected static string $layout = 'filament-panels::components.layout.base';

    public $cartItems = [];

    public function mount(): void
    {
        $this->refreshCart();
    }

    public function refreshCart(): void
    {
        $this->cartItems = TenantsCartItem::with('product')->get();
    }

    public function incrementQuantity(Product $product): void
    {
        $this->addCart($product);
        $this->dispatch('cartUpdated', $this->cartItems);
    }

    public function decrementQuantity(Product $product): void
    {
        $this->reduceCart($product);
        $this->dispatch('cartUpdated', $this->cartItems);
    }
}
