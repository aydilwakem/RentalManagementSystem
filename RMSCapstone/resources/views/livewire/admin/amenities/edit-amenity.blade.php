<div class="min-h-[550px] container mx-auto p-6 bg-white rounded-lg">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Room Category') }}
        </h2>
    </x-slot>
    <div class="shadow-lg rounded-lg p-6 max-w-2xl mx-auto border mt-16 bg-white">
        <h2 class="mb-4 text-xl font-bold text-gray-900 text-center">Edit Amenity</h2>

        <form wire:submit.prevent="">
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                <!-- Name of Amenity -->
                <div class="sm:col-span-2">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Amenity Name</label>
                    <input type="text" wire:model="name" id="name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                        placeholder="Type amenity name" required>
                    @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-between items-center space-y-2 mt-6">
                <x-button onclick="history.back()" type="button"
                    class="!bg-gray-200 !text-black hover:!bg-gray-300 focus:!ring-2 focus:!ring-gray-400 focus:!outline-none">
                    Cancel
                </x-button>
                <x-button type="submit" class="mt-4" wire:click="confirmEdit({{ $amenity->id }})">
                    Save Changes
                </x-button>
            </div>
        </form>
    </div>

    <!-- Edit Confirmation Modal -->
    <x-dialog-modal wire:model.live="confirmEditItem">
        <x-slot name="title">
            {{ __('Edit Amenity') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to save changes on this item?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('confirmEditItem', false)" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ms-3 bg-green text-white" wire:click="updateAmenity({{ $amenity->id }})"
                wire:loading.attr="disabled">
                {{ __('Edit Amenity') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>