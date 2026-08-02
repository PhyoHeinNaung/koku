<?php

namespace App\Livewire\Admin\Products;

use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class VariantImages extends Component
{
    use WithFileUploads;

    public ProductVariant $variant;

    public array $newImages = [];

    public function updatedNewImages(): void
    {
        $this->validate([
            'newImages' => ['array', 'max:6'],
            'newImages.*' => ['mimes:jpeg,png,jpg,gif,svg,webp,avif', 'max:2048'],
        ]);

        $nextOrder = ($this->variant->images()->max('sort_order') ?? -1) + 1;
        $hasPrimary = $this->variant->images()->where('is_primary', true)->exists();

        foreach ($this->newImages as $index => $file) {
            $path = $file->store('products', 'public');

            $this->variant->images()->create([
                'image_url' => $path,
                'is_primary' => ! $hasPrimary && $index === 0,
                'sort_order' => $nextOrder + $index,
            ]);
        }

        $this->reset('newImages');
        $this->dispatch('admin-notify', type: 'success', message: 'Variant images uploaded.');
    }

    public function setPrimary(ProductImage $image): void
    {
        $this->ensureBelongsToVariant($image);

        DB::transaction(function () use ($image) {
            $this->variant->images()->update(['is_primary' => false]);
            $image->update(['is_primary' => true]);
        });

        $this->dispatch('admin-notify', type: 'success', message: 'Primary image updated.');
    }

    public function reorderImage(int $imageId, int $position): void
    {
        $image = $this->variant->images()->findOrFail($imageId);
        $orderedIds = $this->variant->images()
            ->where('id', '!=', $image->id)
            ->orderBy('sort_order')
            ->pluck('id')
            ->all();

        $position = max(0, min($position, count($orderedIds)));
        array_splice($orderedIds, $position, 0, [$image->id]);

        DB::transaction(function () use ($orderedIds) {
            foreach ($orderedIds as $sortOrder => $id) {
                $this->variant->images()->whereKey($id)->update(['sort_order' => $sortOrder]);
            }
        });

        $this->dispatch('admin-notify', type: 'success', message: 'Image order updated.');
    }

    public function deleteImage(ProductImage $image): void
    {
        $this->ensureBelongsToVariant($image);

        $wasPrimary = $image->is_primary;
        $path = $image->image_url;
        $image->delete();

        if ($wasPrimary) {
            $newPrimary = $this->variant->images()->orderBy('sort_order')->first();

            if ($newPrimary) {
                $newPrimary->update(['is_primary' => true]);
            }
        }

        Storage::disk('public')->delete($path);

        $this->dispatch('admin-notify', type: 'success', message: 'Variant image deleted.');
    }

    private function ensureBelongsToVariant(ProductImage $image): void
    {
        abort_unless($image->variant_id === $this->variant->id, 404);
    }

    public function render()
    {
        return view('livewire.admin.products.variant-images', [
            'images' => $this->variant->images()->orderBy('sort_order')->get(),
        ]);
    }
}
