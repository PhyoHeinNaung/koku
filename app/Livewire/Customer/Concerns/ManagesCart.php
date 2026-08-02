<?php

namespace App\Livewire\Customer\Concerns;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;

trait ManagesCart
{
    protected function cartOwnerConditions(): array
    {
        return Auth::check()
            ? ['user_id' => Auth::id(), 'session_id' => null]
            : ['user_id' => null, 'session_id' => session()->getId()];
    }

    protected function currentCart(): ?Cart
    {
        return Cart::where($this->cartOwnerConditions())->whereNull('expired_at')->first();
    }

    protected function getOrCreateCart(): Cart
    {
        return Cart::firstOrCreate([...$this->cartOwnerConditions(), 'expired_at' => null]);
    }

    public function addToCart(int $variantId, int $quantity = 1): void
    {
        $variant = ProductVariant::findOrFail($variantId);

        if (! $variant->is_active || $variant->stock_quantity < 1) {
            return;
        }

        $cart = $this->getOrCreateCart();
        $existing = $cart->items()->where('variant_id', $variantId)->first();

        $newQuantity = min(($existing?->quantity ?? 0) + $quantity, $variant->stock_quantity);

        $cart->items()->updateOrCreate(
            ['variant_id' => $variantId],
            ['quantity' => $newQuantity, 'unit_price' => $variant->price]
        );

        $this->dispatch('cart-updated', open: true);
    }

    public function updateCartItemQuantity(int $cartItemId, int $quantity): void
    {
        $item = CartItem::whereHas('cart', fn ($q) => $q->where($this->cartOwnerConditions()))
            ->findOrFail($cartItemId);

        $item->update(['quantity' => max(1, min($quantity, $item->variant->stock_quantity))]);

        $this->dispatch('cart-updated', open: false);
    }

    public function removeCartItem(int $cartItemId): void
    {
        CartItem::whereHas('cart', fn ($q) => $q->where($this->cartOwnerConditions()))
            ->where('id', $cartItemId)
            ->delete();

        $this->dispatch('cart-updated', open: false);
    }
}
