<div>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Amenity') }}
        </h2>
    </x-slot>

    <!-- Body Container -->
    <div class="py-3">
        <div class="mx-auto max-w-2xl sm:px-6 lg:px-8 bg-white rounded-xl border shadow-md p-6">

            <div class="relative flex items-center mb-4">
                <!-- Back Button -->
                <button onclick="window.location.href='{{ route('admin.amenities') }}'"
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute top-0 right-0">
                    <span class="leading-none translate-y-[-3px]">&times;</span>
                </button>
            </div>


            <!-- Name -->
            <h1 class="text-3xl font-bold text-gray-800 text-center mb-8 pt-4">
                Amenity: {{ $amenity->name }}
            </h1>

            <!-- Action Buttons -->
            <div
                class="flex flex-col sm:flex-row items-center justify-center sm:justify-between space-y-4 sm:space-y-0 sm:space-x-4 mt-6 pt-4">
                <x-ghost-button type="button" icon="fas fa-pen-to-square" wire:navigate
                    href="{{ route('admin.edit-amenity', ['amenity' => $amenity->id]) }}" class="w-full sm:w-auto">
                    Edit
                </x-ghost-button>
                <x-danger-button type="button" icon="fas fa-trash" wire:click="confirmDelete({{ $amenity->id }})"
                    class="w-full sm:w-auto">
                    Delete
                </x-danger-button>
            </div>

            {{-- Delete Confirmation Modal --}}
            <x-dialog-modal wire:model.live="confirmItemDelete" type="danger">
                <x-slot name="title">
                    {{ __('Delete Amenity') }}
                </x-slot>

                <x-slot name="content">
                    {{ __('Are you sure you want to delete this item?') }}
                </x-slot>

                <x-slot name="footer">
                    <x-secondary-button wire:click="$set('confirmItemDelete', false)" wire:loading.attr="disabled">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-danger-button class="ms-3" wire:click="deleteAmenity({{ $amenity->id }})"
                        wire:loading.attr="disabled">
                        {{ __('Delete Amenity') }}
                    </x-danger-button>
                </x-slot>
            </x-dialog-modal>
        </div>
    </div>
</div>
