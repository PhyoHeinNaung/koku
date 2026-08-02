<?php

namespace App\Livewire\Customer\Wishlist;

use App\Models\WishlistItem;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class Icon extends Component
{
    public int $count = 0;

    public function mount(): void
    {
        $this->refreshCount();
    }

    #[On('wishlist-updated')]
    public function refreshCount(): void
    {
        $conditions = Auth::check()
            ? ['user_id' => Auth::id(), 'session_id' => null]
            : ['user_id' => null, 'session_id' => session()->getId()];

        $this->count = WishlistItem::where($conditions)->count();
    }

    public function render()
    {
        return view('livewire.customer.wishlist.icon');
    }
}
