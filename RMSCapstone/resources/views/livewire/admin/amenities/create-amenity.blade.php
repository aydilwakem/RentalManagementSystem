<div>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Amenity') }}
        </h2>
    </x-slot>

    <!-- Body Container -->
    <div class="py-3">
        <div class="mx-auto max-w-2xl sm:px-6 lg:px-8 bg-white rounded-xl border shadow-md p-6">

            <div class="relative flex items-center mb-4">
                <!-- Title -->
                <h2 class="text-2xl font-bold text-gray-900 w-full text-center">Add New Amenity</h2>

                <!-- Back Button -->
                <button onclick="window.location.href='{{ route('admin.amenities') }}'"
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute right-0 translate-y-[-12px]">
                    <span class="leading-none translate-y-[-3px]">&times;</span>
                </button>
            </div>

        <!-- Form Container -->
        <form wire:submit.prevent="" class="flex flex-col h-full space-y-6 min-h-[200px]">
            <div class="mt-4">
                <label for="name" class="block mb-2 text-sm font-semibold text-gray-800">Amenity Name <span class="text-red-500">*</span></label>
                <input type="text" wire:model.live="name" id="name"
                    class="block w-full p-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-900 placeholder-gray-400 focus:ring-green-600 focus:border-green-600 focus:outline-none sm:text-base"
                    placeholder="Ex. Free Wi-Fi, Toiletries, Kettle" required autocomplete="off">
                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Space to push buttons to bottom -->
            <div class="flex-grow"></div>

            <!-- Action Buttons -->
            <div class="flex justify-between space-x-3 pt-4 mt-auto">
                <x-ghost-button onclick="history.back()" type="button">
                    Cancel
                </x-ghost-button>
                <x-button wire:loading.attr="disabled" wire:click="confirmCreate">
                    Create Amenity
                </x-button>
            </div>
        </form>


        <x-dialog-modal wire:model.live="confirmCreateItem">
            <x-slot name="title">
                {{ __('Confirm Amenity Creation') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to create this amenity?') }}
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('confirmCreateItem', false)" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-button class="ms-3" wire:click="saveAmenity"
                    wire:loading.attr="disabled">
                    {{ __('Create Amenity') }}
                </x-button>
            </x-slot>
        </x-dialog-modal>
    </div>
</div>
