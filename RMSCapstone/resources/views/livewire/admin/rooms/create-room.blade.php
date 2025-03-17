<div class="border rounded-lg p-6 max-w-2xl mx-auto mb-6 mt-6">
    <div class="mx-auto max-w-2xl lg:py-2s">
        <h2 class="mb-4 text-xl font-bold text-gray-900 text-center">Add New Room</h2>

        {{-- Display Validation Errors --}}
        @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            <ul>
                @foreach ($errors->all() as $error)
                <li class="py-1">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form wire:submit.prevent="saveRoom">
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <!-- Room Name -->
                <div class="sm:col-span-2">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Room Name</label>
                    <input type="text" wire:model="name" id="name" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                        placeholder="Enter room name">
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
                </div>

                <!-- Ideal Guest -->
                <div>
                    <label for="ideal_guest" class="block mb-2 text-sm font-medium text-gray-900">Ideal
                        Guest</label>
                    <input type="number" wire:model="ideal_guest" id="ideal_guest"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                </div>

                <!-- Max Adults -->
                <div>
                    <label for="max_adults" class="block mb-2 text-sm font-medium text-gray-900">Max Adults</label>
                    <input type="number" wire:model="max_adults" id="max_adults" min="0"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                </div>

                <!-- Max Kids -->
                <div>
                    <label for="max_kids" class="block mb-2 text-sm font-medium text-gray-900">Max Kids</label>
                    <input type="number" wire:model="max_kids" id="max_kids" min="0"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                </div>

                <!-- Turnover Duration -->
                <div>
                    <label for="turnover_duration" class="block mb-2 text-sm font-medium text-gray-900">Turnover
                        Duration (Hours)</label>
                    <input type="number" wire:model="turnover_duration" id="turnover_duration" min="1"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                </div>

                <!-- Room Status -->
                <div class="sm:col-span-2">
                    <label for="room_status" class="block mb-2 text-sm font-medium text-gray-900">Room
                        Status</label>
                    <select wire:model="room_status" id="room_status"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                        <option value="Available">Available</option>
                        <option value="Booked">Booked</option>
                        <option value="Out of Service">Out of Service</option>
                    </select>
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
                <x-button onclick="history.back()" type="button" class="!bg-gray-200 !text-black hover:!bg-gray-300 focus:!ring-2 focus:!ring-gray-400 focus:!outline-none">
                    Cancel
                </x-button>
                <x-button wire:loading.attr="disabled" wire:target="image">
                    Add Room
                </x-button>
            </div>
        </form>
    </div>
</div>
