<?php

namespace App\Livewire\Customer\Shop;

use App\Livewire\Customer\Concerns\ManagesCart;
use App\Livewire\Customer\Concerns\ManagesWishlist;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\Review;
use App\Models\ReviewImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Show extends Component
{
    use ManagesCart;
    use ManagesWishlist;
    use WithFileUploads;

    public Product $product;

    public ?int $selectedVariantId = null;

    public bool $showAllVariants = false;

    public int $quantity = 1;

    public int $reviewRating = 0;

    public string $reviewComment = '';

    public array $reviewPhotos = [];

    public bool $reviewEditing = true;

    public function mount(Product $product): void
    {
        abort_unless($product->is_active, 404);

        $this->product = $product->load(['brand', 'category', 'specification', 'variants' => function ($query) {
            $query->orderByDesc('is_default')->orderBy('id');
        }, 'variants.images', 'variants.specification']);

        abort_unless(
            $this->product->variants->contains(fn ($variant) => $variant->is_active && $variant->is_default),
            404
        );

        $default = $this->product->defaultVariant();

        $this->selectedVariantId = $default?->id;

        if (Auth::check()) {
            $review = Review::with('images')->where('user_id', Auth::id())->where('product_id', $this->product->id)->first();
            if ($review) {
                $this->reviewRating = $review->rating;
                $this->reviewComment = (string) $review->comment;
                $this->reviewEditing = false;
            }
        }
    }

    public function selectVariant(int $variantId): void
    {
        abort_unless(
            $this->product->variants->contains(fn ($variant) => $variant->id === $variantId && $variant->is_active),
            404
        );

        $this->selectedVariantId = $variantId;
        $this->quantity = 1;
        $this->showAllVariants = false;
    }

    public function incrementQuantity(): void
    {
        if ($this->selectedVariant) {
            $this->quantity = min($this->quantity + 1, $this->selectedVariant->stock_quantity);
        }
    }

    public function decrementQuantity(): void
    {
        $this->quantity = max(1, $this->quantity - 1);
    }

    public function addSelectedToCart(): void
    {
        if ($this->selectedVariantId) {
            $this->addToCart($this->selectedVariantId, $this->quantity);
        }
    }

    public function getSelectedVariantProperty()
    {
        return $this->product->variants->firstWhere('id', $this->selectedVariantId);
    }

    public function getActiveVariantsProperty()
    {
        return $this->product->variants->where('is_active', true)->values();
    }

    public function getGalleryImagesProperty(): array
    {
        return $this->selectedVariant
            ? $this->selectedVariant->images->map(fn ($image) => \Storage::url($image->image_url))->values()->all()
            : [];
    }

    public function getGalleryActiveIndexProperty(): int
    {
        if (! $this->selectedVariant) {
            return 0;
        }

        $index = $this->selectedVariant->images->search(fn ($image) => $image->is_primary);

        return $index === false ? 0 : $index;
    }

    public function buyNow(): void
    {
        if ($this->selectedVariantId) {
            $this->addToCart($this->selectedVariantId, $this->quantity);
            $this->redirectRoute('cart.index');
        }
    }

    public function saveReview(): void
    {
        abort_unless(Auth::check(), 403);

        $orderItem = $this->reviewOrderItem();
        abort_unless($orderItem, 403, 'Only customers who received this watch can review it.');

        $validated = $this->validate([
            'reviewRating' => ['required', 'integer', 'between:1,5'],
            'reviewComment' => ['nullable', 'string', 'max:2000'],
            'reviewPhotos' => ['array', 'max:3'],
            'reviewPhotos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $review = Review::updateOrCreate(
            ['user_id' => Auth::id(), 'product_id' => $this->product->id],
            ['order_item_id' => $orderItem->id, 'rating' => $validated['reviewRating'], 'comment' => filled($validated['reviewComment']) ? trim($validated['reviewComment']) : null, 'status' => 'pending', 'verified_purchase' => true]
        );

        $existingImageCount = $review->images()->count();
        $remaining = max(0, 3 - $existingImageCount);
        foreach (array_slice($this->reviewPhotos, 0, $remaining) as $index => $photo) {
            $review->images()->create([
                'image_path' => $photo->store('reviews', 'public'),
                'sort_order' => $existingImageCount + $index,
                'status' => 'pending',
            ]);
        }

        $this->reviewPhotos = [];
        $this->reviewEditing = false;
        session()->flash('review-success', 'Thank you. Your review was sent for approval.');
    }

    public function editReview(): void
    {
        abort_unless($this->ownReview, 404);
        $this->reviewEditing = true;
    }

    public function deleteReviewImage(int $imageId): void
    {
        $image = ReviewImage::whereKey($imageId)->whereHas('review', fn ($query) => $query->where('user_id', Auth::id())->where('product_id', $this->product->id))->firstOrFail();
        Storage::disk('public')->delete($image->image_path);
        $image->delete();
    }

    public function deleteReview(): void
    {
        $review = Review::with('images')->where('user_id', Auth::id())->where('product_id', $this->product->id)->firstOrFail();
        foreach ($review->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }
        $review->delete();
        $this->reset('reviewRating', 'reviewComment', 'reviewPhotos');
        $this->reviewEditing = true;
        session()->flash('review-success', 'Your review was removed.');
    }

    public function getCanReviewProperty(): bool
    {
        return Auth::check() && (bool) $this->reviewOrderItem();
    }

    public function getOwnReviewProperty(): ?Review
    {
        return Auth::check() ? Review::with('images')->where('user_id', Auth::id())->where('product_id', $this->product->id)->first() : null;
    }

    private function reviewOrderItem(): ?OrderItem
    {
        if (! Auth::check()) {
            return null;
        }

        return OrderItem::query()
            ->whereHas('order', fn ($query) => $query->where('user_id', Auth::id())->where('status', 'delivered'))
            ->whereHas('variant', fn ($query) => $query->where('product_id', $this->product->id))
            ->latest('id')
            ->first();
    }

    /**
     * Spec groups for the accordion, each with a heading and the fields that
     * belong under it. Only groups (and fields within them) that actually
     * have a value get rendered — empty groups are skipped entirely.
     */
    public function getSpecGroupsProperty(): array
    {
        if (! $this->selectedVariant) {
            return [];
        }

        $spec = $this->selectedVariant->effectiveSpecifications($this->product->specification);

        $groups = [
            'Case' => [
                'Case Size' => $spec['case_size'],
                'Case Material' => $spec['case_material'],
                'Case Thickness' => $spec['case_thickness'],
                'Water Resistance' => $spec['water_resistance'],
                'Crystal' => $spec['glass_type'],
                'Weight' => $spec['weight'],
                'Dial Color' => $spec['dial_color'],
            ],
            'Movement' => [
                'Caliber' => $spec['movement_caliber'],
                'Power Reserve' => $spec['power_reserve'],
                'Frequency' => $spec['frequency'],
                'Jewels' => $spec['jewels'],
                'Functions' => $spec['functions'],
            ],
            'Strap' => [
                'Material' => $spec['strap_material'],
                'Clasp Type' => $spec['clasp_type'],
            ],
            'Smart Features' => [
                'Battery Life' => $spec['battery_life'],
                'Display Type' => $spec['display_type'],
                'Connectivity' => $spec['connectivity'],
                'Compatibility' => $spec['compatibility'],
            ],
            'Origin' => [
                'Country of Origin' => $spec['country_of_origin'],
            ],
        ];

        if (! empty($spec['custom_specifications'])) {
            $groups['Additional Details'] = $spec['custom_specifications'];
        }

        return collect($groups)
            ->map(fn ($fields) => array_filter($fields, fn ($value) => filled($value)))
            ->filter(fn ($fields) => count($fields) > 0)
            ->toArray();
    }

    public function render()
    {
        $approvedReviewQuery = Review::query()
            ->with(['user', 'images' => fn ($query) => $query->where('status', 'approved')])
            ->where('product_id', $this->product->id)
            ->where('status', 'approved');

        $allApprovedReviews = (clone $approvedReviewQuery)->get();
        $approvedReviews = $approvedReviewQuery->when(Auth::check(), fn ($query) => $query->where('user_id', '!=', Auth::id()))->latest()->get();

        $reviewCount = $allApprovedReviews->count();
        $reviewAverage = $reviewCount ? round((float) $allApprovedReviews->avg('rating'), 1) : 0;
        $ratingDistribution = collect(range(5, 1))->mapWithKeys(fn ($rating) => [$rating => $reviewCount ? round($allApprovedReviews->where('rating', $rating)->count() / $reviewCount * 100) : 0]);

        $relatedProducts = Product::query()
            ->with(['brand', 'variants.images'])
            ->withMin('variants', 'price')
            ->where('is_active', true)
            ->where('id', '!=', $this->product->id)
            ->where(function ($query) {
                $query->where('category_id', $this->product->category_id)
                    ->orWhere('brand_id', $this->product->brand_id);
            })
            ->whereHas('variants', fn ($query) => $query->where('is_active', true))
            ->take(3)
            ->get();

        return view('livewire.customer.shop.show', compact('relatedProducts', 'approvedReviews', 'reviewCount', 'reviewAverage', 'ratingDistribution'))
            ->layout('layouts.app', ['overlay' => false]);
    }
}
