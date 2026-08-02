<div class="px-6 sm:px-10 lg:px-16 py-10 max-w-xl">

    <h1 class="text-2xl font-bold text-gray-900 mb-6">
        {{ $address ? 'Edit Address' : 'Add Address' }}
    </h1>

    <form wire:submit="save" class="bg-white border border-gray-200 rounded-xl p-6 space-y-5">

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Label (optional)</label>
            <input type="text" wire:model="label" placeholder="e.g. Home, Work"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <input type="text" wire:model="full_name"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900">
                @error('full_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                <input type="text" wire:model="phone"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900">
                @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Country</label>
            <input type="text" wire:model="country"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900">
            @error('country') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">State / Region</label>
                <input type="text" wire:model="state_region"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">City / Township</label>
                <input type="text" wire:model="city"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900">
                @error('city') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">District / Area</label>
                <input type="text" wire:model="district_area"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Postal Code</label>
                <input type="text" wire:model="postal_code"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Address Line 1</label>
            <input type="text" wire:model="address_line1"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900">
            @error('address_line1') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Address Line 2 (optional)</label>
            <input type="text" wire:model="address_line2"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900">
        </div>

        <label class="flex items-center gap-2">
            <input type="checkbox" wire:model="is_default" class="rounded border-gray-300">
            <span class="text-sm text-gray-700">Set as default address</span>
        </label>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit"
                class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800">
                Save Address
            </button>
            <a href="{{ route('addresses.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                Cancel
            </a>
        </div>

    </form>
</div>