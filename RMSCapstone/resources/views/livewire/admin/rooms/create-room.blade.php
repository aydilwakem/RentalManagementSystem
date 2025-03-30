<div class="border rounded-lg p-6 max-w-2xl mx-auto mb-6 mt-6">
    <div class="mx-auto max-w-2xl lg:py-2s">
        <h2 class="mb-4 text-xl font-bold text-gray-900 text-center">Add New Room</h2>

        <form wire:submit.prevent="">
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <!-- Room Name -->
                <div class="sm:col-span-2">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Room Name</label>
                    <input type="text" wire:model="name" id="name" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                        placeholder="Enter room name">
                    @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Room Category -->
                <div class="sm:col-span-2">
                    <label for="room_category_id" class="block mb-2 text-sm font-medium text-gray-900">Room
                        Category</label>
                    <select wire:model="room_category_id" id="room_category_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                        <option value="">Select Category</option>
                        @foreach ($roomCategories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('room_category_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Ideal Guest -->
                <div>
                    <label for="ideal_guest" class="block mb-2 text-sm font-medium text-gray-900">Ideal
                        Guest</label>
                    <input type="number" wire:model="ideal_guest" id="ideal_guest"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    @error('ideal_guest')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Max Adults -->
                <div>
                    <label for="max_adults" class="block mb-2 text-sm font-medium text-gray-900">Max Adults</label>
                    <input type="number" wire:model="max_adults" id="max_adults" min="0"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    @error('max_adults')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Max Kids -->
                <div>
                    <label for="max_kids" class="block mb-2 text-sm font-medium text-gray-900">Max Kids</label>
                    <input type="number" wire:model="max_kids" id="max_kids" min="0"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    @error('max_kids')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Turnover Duration -->
                <div>
                    <label for="turnover_duration" class="block mb-2 text-sm font-medium text-gray-900">Turnover
                        Duration (Hours)</label>
                    <input type="number" wire:model="turnover_duration" id="turnover_duration" min="1"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    @error('turnover_duration')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Room Status -->
                <div>
                    <label for="room_status" class="block mb-2 text-sm font-medium text-gray-900">Room
                        Status</label>
                    <select wire:model="room_status" id="room_status"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                        <option value="Available">Available</option>
                        <option value="Booked">Booked</option>
                        <option value="Out of Service">Out of Service</option>
                    </select>
                    @error('room_status')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Base Rate -->
                <div>
                    <label for="base_rate" class="block mb-2 text-sm font-medium text-gray-900">Base Rate</label>
                    <input type="base_rate" wire:model="base_rate" id="base_rate"
                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500"
                        placeholder="Enter base_rate">
                    @error('base_rate')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>


                <!-- Image Upload -->
                <div class="sm:col-span-2">
                    <label for="image" class="block mb-2 text-sm font-medium text-gray-900">Upload Image</label>
                    <input type="file" wire:model="image" id="image" accept="image/png, image/jpeg"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">

                    @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <div wire:loading wire:target="image" class="mt-2 text-gray-600">
                        Uploading image...
                    </div>

                    @if ($image && method_exists($image, 'temporaryUrl'))
                        <div class="mt-2">
                            <img src="{{ $image->temporaryUrl() }}" class="w-32 h-32 object-cover rounded-lg shadow">
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex justify-between items-center space-y-2 mt-6">
                <x-button onclick="history.back()" type="button"
                    class="!bg-gray-200 !text-black hover:!bg-gray-300 focus:!ring-2 focus:!ring-gray-400 focus:!outline-none">
                    Cancel
                </x-button>
                <x-button wire:loading.attr="disabled" wire:target="image" wire:click="confirmCreate">
                    Add Room
                </x-button>
            </div>
        </form>
    </div>
    <!-- Create Confirmation Modal -->
    <x-dialog-modal wire:model.live="confirmCreateItem">
        <x-slot name="title">
            {{ __('Create Room') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to add this item?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('confirmCreateItem', false)" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ms-3 bg-green text-white" wire:click="saveRoom" wire:loading.attr="disabled">
                {{ __('Create Room') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>

</div>
</div> <!-- Try to delete this -->