<?php

namespace App\Livewire\Customer\Wishlist;

use App\Livewire\Customer\Concerns\ManagesWishlist;
use App\Models\Product;
use App\Models\WishlistItem;
use Livewire\Component;

class Index extends Component
{
    use ManagesWishlist;

    public function removeFromWishlist(int $productId): void
    {
        WishlistItem::where($this->wishlistOwnerConditions())->where('product_id', $productId)->delete();
        $this->dispatch('wishlist-updated');
    }

    public function render()
    {
        $conditions = $this->wishlistOwnerConditions();

        $products = Product::query()
            ->whereHas('wishlistItems', fn ($q) => $q->where($conditions))
            ->with(['brand', 'variants.images'])
            ->withMin('variants', 'price')
            ->get();

        return view('livewire.customer.wishlist.index', ['products' => $products])
            ->layout('layouts.app', ['overlay' => false]);
    }
}
