<?php

namespace App\Livewire\Admin\Shipping;

use App\Models\ShippingLocation;
use App\Models\ShippingZone;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public string $tab = 'zones';

    #[Url]
    public string $search = '';

    #[Url]
    public string $status = 'all';

    #[Url]
    public string $sort = 'newest';

    /** @var array<int, int> */
    public array $selected = [];

    public bool $editorOpen = false;

    public string $editorType = 'zone';

    public ?int $editingId = null;

    public string $name = '';

    public string $fee = '';

    public string $estimatedDays = '';

    public string $description = '';

    public bool $zoneActive = true;

    public string $zoneId = '';

    public string $country = 'Myanmar';

    public string $stateRegion = '';

    public string $city = '';

    public string $districtArea = '';

    public bool $locationActive = true;

    public function updatedTab(): void
    {
        if (! in_array($this->tab, ['zones', 'locations'], true)) {
            $this->tab = 'zones';
        }

        $this->search = '';
        $this->status = 'all';
        $this->sort = 'newest';
        $this->resetPage();
        $this->clearSelection();
        $this->closeEditor();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
        $this->clearSelection();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
        $this->clearSelection();
    }

    public function updatedSort(): void
    {
        $this->resetPage();
        $this->clearSelection();
    }

    public function openCreate(string $type): void
    {
        abort_unless(in_array($type, ['zone', 'location'], true), 404);

        $this->resetEditor();
        $this->editorType = $type;
        $this->editorOpen = true;
    }

    public function editZone(int $zoneId): void
    {
        $zone = ShippingZone::findOrFail($zoneId);

        $this->resetEditor();
        $this->editorType = 'zone';
        $this->editingId = $zone->id;
        $this->name = $zone->name;
        $this->fee = (string) $zone->fee;
        $this->estimatedDays = $zone->estimated_days ?? '';
        $this->description = $zone->description ?? '';
        $this->zoneActive = $zone->is_active;
        $this->editorOpen = true;
    }

    public function editLocation(int $locationId): void
    {
        $location = ShippingLocation::findOrFail($locationId);

        $this->resetEditor();
        $this->editorType = 'location';
        $this->editingId = $location->id;
        $this->zoneId = (string) $location->shipping_zone_id;
        $this->country = $location->country;
        $this->stateRegion = $location->state_region ?? '';
        $this->city = $location->city;
        $this->districtArea = $location->district_area ?? '';
        $this->locationActive = $location->is_active;
        $this->editorOpen = true;
    }

    public function saveZone(): void
    {
        $validated = $this->validate([
            'name' => [
                'required',
                'string',
                'max:120',
                Rule::unique('shipping_zones', 'name')->ignore($this->editingId),
            ],
            'fee' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'estimatedDays' => ['nullable', 'string', 'max:80'],
            'description' => ['nullable', 'string', 'max:1000'],
            'zoneActive' => ['boolean'],
        ]);

        $zone = $this->editingId
            ? ShippingZone::findOrFail($this->editingId)
            : new ShippingZone;

        $zone->fill([
            'name' => $validated['name'],
            'fee' => $validated['fee'],
            'estimated_days' => filled($validated['estimatedDays']) ? $validated['estimatedDays'] : null,
            'description' => filled($validated['description']) ? $validated['description'] : null,
            'is_active' => $validated['zoneActive'],
        ])->save();

        $message = $this->editingId ? 'Shipping zone updated.' : 'Shipping zone created.';
        $this->closeEditor();
        $this->dispatch('admin-notify', type: 'success', message: $message);
    }

    public function saveLocation(): void
    {
        $validated = $this->validate([
            'zoneId' => ['required', 'integer', Rule::exists('shipping_zones', 'id')],
            'country' => ['required', 'string', 'max:100'],
            'stateRegion' => ['nullable', 'string', 'max:120'],
            'city' => ['required', 'string', 'max:120'],
            'districtArea' => ['nullable', 'string', 'max:120'],
            'locationActive' => ['boolean'],
        ]);

        $duplicate = ShippingLocation::query()
            ->where('shipping_zone_id', $validated['zoneId'])
            ->where('country', $validated['country'])
            ->where('state_region', filled($validated['stateRegion']) ? $validated['stateRegion'] : null)
            ->where('city', $validated['city'])
            ->where('district_area', filled($validated['districtArea']) ? $validated['districtArea'] : null)
            ->when($this->editingId, fn (Builder $query) => $query->whereKeyNot($this->editingId))
            ->exists();

        if ($duplicate) {
            $this->addError('city', 'This shipping location already exists in the selected zone.');

            return;
        }

        $location = $this->editingId
            ? ShippingLocation::findOrFail($this->editingId)
            : new ShippingLocation;

        $location->fill([
            'shipping_zone_id' => $validated['zoneId'],
            'country' => $validated['country'],
            'state_region' => filled($validated['stateRegion']) ? $validated['stateRegion'] : null,
            'city' => $validated['city'],
            'district_area' => filled($validated['districtArea']) ? $validated['districtArea'] : null,
            'is_active' => $validated['locationActive'],
        ])->save();

        $message = $this->editingId ? 'Shipping location updated.' : 'Shipping location created.';
        $this->closeEditor();
        $this->dispatch('admin-notify', type: 'success', message: $message);
    }

    public function closeEditor(): void
    {
        $this->editorOpen = false;
        $this->resetEditor();
    }

    public function deleteZone(ShippingZone $zone): void
    {
        if ($zone->locations()->exists()) {
            $this->dispatch(
                'admin-notify',
                type: 'warning',
                message: 'Move or remove this zone’s locations before deleting it.'
            );

            return;
        }

        $zone->delete();
        $this->dispatch('admin-notify', type: 'success', message: 'Shipping zone deleted.');
    }

    public function deleteLocation(ShippingLocation $location): void
    {
        if ($location->orders()->exists()) {
            $this->dispatch(
                'admin-notify',
                type: 'warning',
                message: 'Locations referenced by orders cannot be deleted. Deactivate it instead.'
            );

            return;
        }

        $location->delete();
        $this->dispatch('admin-notify', type: 'success', message: 'Shipping location deleted.');
    }

    /** @param array<int, int|string> $ids */
    public function togglePageSelection(array $ids): void
    {
        $ids = array_map('intval', $ids);
        $selected = array_map('intval', $this->selected);
        $allSelected = count(array_intersect($ids, $selected)) === count($ids);

        $this->selected = $allSelected
            ? array_values(array_diff($selected, $ids))
            : array_values(array_unique([...$selected, ...$ids]));
    }

    public function clearSelection(): void
    {
        $this->selected = [];
    }

    public function bulkSetActive(bool $active): void
    {
        if ($this->selected === []) {
            return;
        }

        $model = $this->tab === 'zones' ? ShippingZone::class : ShippingLocation::class;
        $count = $model::whereKey($this->selected)->update(['is_active' => $active]);
        $this->clearSelection();

        $this->dispatch('admin-notify', type: 'success', message: "{$count} shipping records updated.");
    }

    public function bulkUpdateStatus(bool $active): void
    {
        $this->bulkSetActive($active);
    }

    public function saveEditor(): void
    {
        $this->editorType === 'zone' ? $this->saveZone() : $this->saveLocation();
    }

    public function bulkDelete(): void
    {
        if ($this->selected === []) {
            return;
        }

        if ($this->tab === 'zones') {
            $records = ShippingZone::whereKey($this->selected)->withCount('locations')->get();
            $deletable = $records->where('locations_count', 0);
        } else {
            $records = ShippingLocation::whereKey($this->selected)->withCount('orders')->get();
            $deletable = $records->where('orders_count', 0);
        }

        $skipped = $records->count() - $deletable->count();
        $model = $this->tab === 'zones' ? ShippingZone::class : ShippingLocation::class;
        $model::whereKey($deletable->pluck('id'))->delete();
        $this->clearSelection();

        $message = $deletable->count().' shipping '.str('record')->plural($deletable->count()).' deleted.';
        if ($skipped > 0) {
            $message .= " {$skipped} linked ".str('record')->plural($skipped).' kept.';
        }

        $this->dispatch('admin-notify', type: $skipped > 0 ? 'warning' : 'success', message: $message);
    }

    public function clearAll(): void
    {
        $this->search = '';
        $this->status = 'all';
        $this->sort = 'newest';
        $this->resetPage();
        $this->clearSelection();
    }

    private function resetEditor(): void
    {
        $this->resetValidation();
        $this->editingId = null;
        $this->name = '';
        $this->fee = '';
        $this->estimatedDays = '';
        $this->description = '';
        $this->zoneActive = true;
        $this->zoneId = '';
        $this->country = 'Myanmar';
        $this->stateRegion = '';
        $this->city = '';
        $this->districtArea = '';
        $this->locationActive = true;
    }

    private function zoneQuery(): Builder
    {
        return ShippingZone::query()
            ->withCount([
                'locations',
                'locations as active_locations_count' => fn (Builder $query) => $query->where('is_active', true),
            ])
            ->when(filled($this->search), function (Builder $query): void {
                $search = trim($this->search);
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('estimated_days', 'like', "%{$search}%");
                });
            })
            ->when($this->status === 'active', fn (Builder $query) => $query->where('is_active', true))
            ->when($this->status === 'inactive', fn (Builder $query) => $query->where('is_active', false))
            ->when($this->sort === 'name', fn (Builder $query) => $query->orderBy('name'))
            ->when($this->sort === 'fee_desc', fn (Builder $query) => $query->orderByDesc('fee'))
            ->when($this->sort === 'fee_asc', fn (Builder $query) => $query->orderBy('fee'))
            ->when($this->sort === 'oldest', fn (Builder $query) => $query->oldest())
            ->when($this->sort === 'newest', fn (Builder $query) => $query->latest());
    }

    private function locationQuery(): Builder
    {
        return ShippingLocation::query()
            ->with('zone')
            ->withCount('orders')
            ->when(filled($this->search), function (Builder $query): void {
                $search = trim($this->search);
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('country', 'like', "%{$search}%")
                        ->orWhere('state_region', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%")
                        ->orWhere('district_area', 'like', "%{$search}%")
                        ->orWhereHas('zone', fn (Builder $query) => $query->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($this->status === 'active', fn (Builder $query) => $query->where('is_active', true))
            ->when($this->status === 'inactive', fn (Builder $query) => $query->where('is_active', false))
            ->when($this->sort === 'name', fn (Builder $query) => $query
                ->orderBy('country')
                ->orderBy('state_region')
                ->orderBy('city'))
            ->when($this->sort === 'zone', fn (Builder $query) => $query
                ->orderBy(
                    ShippingZone::select('name')->whereColumn('shipping_zones.id', 'shipping_locations.shipping_zone_id')
                ))
            ->when($this->sort === 'oldest', fn (Builder $query) => $query->oldest())
            ->when($this->sort === 'newest', fn (Builder $query) => $query->latest());
    }

    public function render()
    {
        $zones = ShippingZone::orderBy('name')->get();
        $resources = $this->tab === 'zones'
            ? $this->zoneQuery()->paginate(10)
            : $this->locationQuery()->paginate(10);

        return view('livewire.admin.shipping.index', [
            'resources' => $resources,
            'zones' => $zones,
            'zoneCount' => ShippingZone::count(),
            'locationCount' => ShippingLocation::count(),
            'hasFilters' => filled($this->search) || $this->status !== 'all' || $this->sort !== 'newest',
        ])->layout('layouts.admin');
    }
}
