<?php

namespace App\Livewire\Customer\Concerns;

use App\Models\WishlistItem;
use Illuminate\Support\Facades\Auth;

trait ManagesWishlist
{
    protected function wishlistOwnerConditions(): array
    {
        return Auth::check()
            ? ['user_id' => Auth::id(), 'session_id' => null]
            : ['user_id' => null, 'session_id' => session()->getId()];
    }

    public function isWishlisted(int $productId): bool
    {
        return WishlistItem::where($this->wishlistOwnerConditions())
            ->where('product_id', $productId)
            ->exists();
    }

    public function toggleWishlist(int $productId): void
    {
        $conditions = $this->wishlistOwnerConditions();

        $existing = WishlistItem::where($conditions)->where('product_id', $productId)->first();

        if ($existing) {
            $existing->delete();
        } else {
            WishlistItem::create([...$conditions, 'product_id' => $productId]);
        }

        $this->dispatch('wishlist-updated');
    }
}
