<?php

namespace App\Livewire\Admin\Reviews;

use App\Models\Review;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $status = 'pending';

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function approve(Review $review): void
    {
        $review->update(['status' => 'approved']);
        $review->images()->update(['status' => 'approved']);
        session()->flash('success', 'Review approved and published.');
    }

    public function reject(Review $review): void
    {
        $review->update(['status' => 'rejected']);
        $review->images()->update(['status' => 'rejected']);
        session()->flash('success', 'Review rejected. You can contact the customer from their email link.');
    }

    public function render()
    {
        $reviews = Review::with(['user', 'product', 'images'])
            ->when($this->status !== 'all', fn ($query) => $query->where('status', $this->status))
            ->latest()->paginate(12);

        return view('livewire.admin.reviews.index', compact('reviews'))->layout('layouts.admin');
    }
}
