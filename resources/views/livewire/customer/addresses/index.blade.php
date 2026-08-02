<div class="px-6 sm:px-10 lg:px-16 py-10 max-w-3xl">

    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Addresses</h1>
        <a href="{{ route('addresses.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800">
            + Add Address
        </a>
    </div>

    @if (session('success'))
        <div class="mb-6 px-4 py-3 bg-green-50 text-green-800 text-sm rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if ($addresses->isEmpty())
        <p class="text-gray-400 text-center py-16">You haven't saved any addresses yet.</p>
    @else
        <div class="space-y-4">
            @foreach ($addresses as $address)
                <div wire:key="address-{{ $address->id }}" class="border border-gray-200 rounded-xl p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <p class="text-sm font-semibold text-gray-900">{{ $address->label ?: 'Address' }}</p>
                                @if ($address->is_default)
                                    <span class="px-2 py-0.5 bg-gray-900 text-white text-[10px] rounded-full">Default</span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-600 mt-1">{{ $address->full_name }} &middot; {{ $address->phone }}</p>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ $address->address_line1 }}{{ $address->address_line2 ? ', ' . $address->address_line2 : '' }},
                                {{ $address->city }}{{ $address->district_area ? ', ' . $address->district_area : '' }},
                                {{ $address->state_region }}, {{ $address->country }}
                                {{ $address->postal_code }}
                            </p>
                        </div>
                        <div class="flex flex-col items-end gap-2 shrink-0">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('addresses.edit', $address) }}"
                                    class="text-sm text-gray-600 hover:text-gray-900">Edit</a>
                                <button type="button" wire:click="deleteAddress({{ $address->id }})"
                                    wire:confirm="Delete this address?" class="text-sm text-red-600 hover:text-red-800">
                                    Delete
                                </button>
                            </div>
                            @unless ($address->is_default)
                                <button type="button" wire:click="setDefault({{ $address->id }})"
                                    class="text-xs text-gray-500 hover:text-gray-900 underline">
                                    Set as default
                                </button>
                            @endunless
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>