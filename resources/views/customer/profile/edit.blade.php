<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="max-w-xl">
                    @include('customer.profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="max-w-xl">
                    @include('customer.profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="max-w-xl">
                    @include('customer.profile.partials.delete-user-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <a href="{{ route('addresses.index') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">
                    Manage saved addresses →
                </a>
                <a href="{{ route('orders.index') }}" class="text-sm text-gray-600 hover:text-gray-900 underline ml-4">
                    View order history →
                </a>
            </div>
        </div>
    </div>
</x-app-layout>