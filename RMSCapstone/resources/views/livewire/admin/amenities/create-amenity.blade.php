<div class="min-h-[550px] container mx-auto p-6 bg-white rounded-lg">
    <div class="border rounded-lg p-6 max-w-2xl mx-auto mb-6 mt-6 shadow-md">
        <div class="mx-auto max-w-2xl lg:py-2">
            <h2 class="mb-4 text-xl font-bold text-gray-900 text-center">Add a New Amenity</h2>


            <form wire:submit.prevent="">
                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                    <!-- Name of Amenity -->
                    <div class="sm:col-span-2">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Name</label>
                        <input type="text" wire:model="name" id="name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            placeholder="Type amenity name" required>
                        @error('name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-between items-center space-y-2 mt-6">
                    <x-button onclick="history.back()" type="button"
                        class="!bg-gray-200 !text-black hover:!bg-gray-300 focus:!ring-2 focus:!ring-gray-400 focus:!outline-none">
                        Cancel
                    </x-button>
                    <x-button wire:loading.attr="disabled" wire:click="confirmCreate">
                        Add Amenity
                    </x-button>
                </div>
            </form>
        </div>
        <!-- Create Confirmation Modal -->
        <x-dialog-modal wire:model.live="confirmCreateItem">
            <x-slot name="title">
                {{ __('Create Amenity') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to add this item?') }}
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('confirmCreateItem', false)" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-button class="ms-3 bg-green text-white" wire:click="saveAmenity" wire:loading.attr="disabled">
                    {{ __('Create Amenity') }}
                </x-button>
            </x-slot>
        </x-dialog-modal>

    </div>
</div>
