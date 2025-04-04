<?php

namespace App\Filament\Tenant\Pages\Traits;

use App\Models\Tenants\CartItem;
use App\Models\Tenants\Product;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;

trait CartInteraction
{
    private function validateStock(Product $product, $qty): bool
    {
        if (! $product->is_non_stock && ($product->stock < 0 || $product->stock < $qty)) {
            Notification::make()
                ->title(__('Stock is out'))
                ->danger()
                ->send();
            $this->mount();

            return false;
        }

        return true;
    }

    public function addCart(Product $product, ?array $data = null)
    {
        $auth = Filament::auth()->id();
        if (! $data) {
            $qty = (
                CartItem::whereProductId($product->getKey())
                    ->cashier()
                    ->first()
                ->qty ?? 0
            ) + 1;
        } else {
            if (!$data['amount']) {
                $this->deleteCart(CartItem::whereProductId($product->getKey())->first());
                $this->mount();
                return;
            }
            $qty = $data['amount'];
        }
        if (! $this->validateStock($product, $qty)) {
            return;
        }
        CartItem::query()
            ->updateOrCreate(
                [
                    'product_id' => $product->getKey(),
                    'user_id' => $auth,
                ],
                [
                    'qty' => $qty,
                    'price' => $product->selling_price * $qty,
                    'user_id' => $auth,
                    'product_id' => $product->getKey(),
                ]
            );
        $this->mount();
    }

    public function reduceCart(Product $product)
    {
        $cartItem = CartItem::whereProductId($product->getKey())
            ->cashier()
            ->first();
        $qty = $cartItem->qty - 1;
        if ($qty == 0) {
            $cartItem->delete();

            $this->mount();

            return;
        }
        $price = $product->selling_price * ($qty);
        $cartItem->fill([
            'qty' => $qty,
            'price' => $price,
        ]);
        $cartItem->save();
        $this->mount();
    }

    public function deleteCart(CartItem $cartItem)
    {
        $cartItem->delete();
        $this->mount();
    }

    public function addDiscountPricePerItem(CartItem $cartItem, $value)
    {
        if (! ($value && $value > 0) && $value > $cartItem->product->selling_price) {
            return;
        }
        $cartItem->discount_price = (float) $value;

        $cartItem->save();
        $this->mount();
    }

    public function updateCart(CartItem $cartItem, $value)
    {
        if ((int) $value == 0) {
            $cartItem->delete();
            $this->mount();

            return;
        }
        $value = $value != '' ? $value : 0;
        if ($cartItem->product->qty <= $value) {
            Notification::make()
                ->title(__('Stock is out'))
                ->danger()
                ->send();
            $this->mount();

            return;
        }
        $price = $cartItem->product->selling_price * ((int) $value);
        $cartItem->fill([
            'qty' => $value,
            'price' => $price,
        ]);
        $cartItem->save();
        $this->mount();
    }

    public function clearCart()
    {
        CartItem::query()
            ->cashier()
            ->delete();

        Notification::make()
            ->title(__('Cart has been cleared'))
            ->success()
            ->send();
        $this->mount();
    }

    public function addCartUsingScanner(string $value)
    {
        $product = Product::whereBarcode($value)
            ->orWhere('sku', $value)
            ->first();

        if (! $product) {
            Notification::make()
                ->title(__('Product not found'))
                ->warning()
                ->send();

            return;
        }

        $stock = 1;

        $cartItem = CartItem::whereProductId($product->getKey())
            ->cashier()
            ->first();
        if ($cartItem) {
            $stock = $cartItem->qty + 1;
        }

        $this->addCart($product, [
            'amount' => $stock,
        ]);
    }
}
