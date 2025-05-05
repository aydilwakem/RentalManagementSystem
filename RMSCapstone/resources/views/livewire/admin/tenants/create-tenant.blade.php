<div class="mx-4 sm:mx-auto bg-white dark:bg-[#2A2A2A] rounded-2xl p-8">
    <h2 class="mb-4 text-xl font-bold text-gray-900 text-center">Add a New Tenant</h2>
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    <form wire:submit.prevent="">
        <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

            <!-- First Name -->
            <div>
                <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900">First Name</label>
                <input type="text" wire:model="first_name" id="first_name"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                    >
                @error('first_name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Middle Name -->
            <div>
                <label for="middle_name" class="block mb-2 text-sm font-medium text-gray-900">Middle
                    Name</label>
                <input type="text" wire:model="middle_name" id="middle_name"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                @error('middle_name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Last Name -->
            <div>
                <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900">Last Name</label>
                <input type="text" wire:model="last_name" id="last_name"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                    >
                @error('last_name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Suffix -->
            <div>
                <label for="suffix" class="block mb-2 text-sm font-medium text-gray-900">Suffix</label>
                <input type="text" wire:model="suffix" id="suffix"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                @error('suffix')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                <input type="email" wire:model="email" id="email"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                    >
                @error('email')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Contact Number -->
            <div>
                <label for="contact_number" class="block mb-2 text-sm font-medium text-gray-900">Contact
                    Number</label>
                <input type="text" wire:model="contact_number" id="contact_number"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                    >
                @error('contact_number')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- House Number -->
            <div>
                <label for="house_number" class="block mb-2 text-sm font-medium text-gray-900">House
                    Number</label>
                <input type="text" wire:model="house_number" id="house_number"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                @error('house_number')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Street -->
            <div>
                <label for="street" class="block mb-2 text-sm font-medium text-gray-900">Street</label>
                <input type="text" wire:model="street" id="street"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                @error('street')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Barangay -->
            <div>
                <label for="barangay" class="block mb-2 text-sm font-medium text-gray-900">Barangay</label>
                <input type="text" wire:model="barangay" id="barangay"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                @error('barangay')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- City/Municipality -->
            <div>
                <label for="city_municipality"
                    class="block mb-2 text-sm font-medium text-gray-900">City/Municipality</label>
                <input type="text" wire:model="city_municipality" id="city_municipality"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                @error('city_municipality')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Province -->
            <div>
                <label for="province" class="block mb-2 text-sm font-medium text-gray-900">Province</label>
                <input type="text" wire:model="province" id="province"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                @error('province')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Region -->
            <div>
                <label for="region" class="block mb-2 text-sm font-medium text-gray-900">Region</label>
                <input type="text" wire:model="region" id="region"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                @error('region')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Postal Code -->
            <div>
                <label for="postal_code" class="block mb-2 text-sm font-medium text-gray-900">Postal
                    Code</label>
                <input type="text" wire:model="postal_code" id="postal_code"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                @error('postal_code')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Country -->
            <div>
                <label for="country" class="block mb-2 text-sm font-medium text-gray-900">Country</label>
                <input type="text" wire:model="country" id="country"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                @error('country')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

        </div>

        <!-- Buttons -->
        <div class="flex justify-between items-center space-y-2 mt-6">
            <x-button onclick="history.back()" type="button"
                class="!bg-gray-200 !text-black hover:!bg-gray-300 focus:!ring-2 focus:!ring-gray-400 focus:!outline-none">
                Cancel
            </x-button>
            <x-button wire:loading.attr="disabled" wire:click="confirmCreate">
                Add Tenant
            </x-button>
        </div>
    </form>

    <!-- Create Confirmation Modal -->
    <x-dialog-modal wire:model.live="confirmCreateItem">
        <x-slot name="title">
            {{ __('Create Tenant') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to add this tenant?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('confirmCreateItem', false)" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ms-3 bg-green text-white" wire:click="saveTenant" wire:loading.attr="disabled">
                {{ __('Create Tenant') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
