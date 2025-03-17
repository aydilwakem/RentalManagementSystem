<style>
    [x-cloak] { display: none !important; }
</style>

<!-- Main container -->
<div class="min-h-[550px] container mx-auto p-6 bg-white rounded-lg" x-data="{ showConfirm: false }">
    <!-- Form container -->
    <div class="shadow-lg rounded-lg p-6 max-w-2xl mx-auto border bg-bwhite">
        <h2 class="mb-4 text-xl font-bold text-gray-900 text-center">Edit Room</h2>
        <form wire:submit.prevent="updateRoom">
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <!-- Room Name -->
                <div class="sm:col-span-2">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Room Name</label>
                    <input type="text" wire:model="name" id="name" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
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
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Ideal Guest -->
                <div>
                    <label for="ideal_guest" class="block mb-2 text-sm font-medium text-gray-900">Ideal
                        Guest</label>
                    <input type="number" wire:model="ideal_guest" id="ideal_guest"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    @error('ideal_guest')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Max Adults -->
                <div>
                    <label for="max_adults" class="block mb-2 text-sm font-medium text-gray-900">Max Adults</label>
                    <input type="number" wire:model="max_adults" id="max_adults" min="0"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    @error('max_adults')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Max Kids -->
                <div>
                    <label for="max_kids" class="block mb-2 text-sm font-medium text-gray-900">Max Kids</label>
                    <input type="number" wire:model="max_kids" id="max_kids" min="0"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    @error('max_kids')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Turnover Duration -->
                <div>
                    <label for="turnover_duration" class="block mb-2 text-sm font-medium text-gray-900">Turnover
                        Duration (Hours)</label>
                    <input type="number" wire:model="turnover_duration" id="turnover_duration" min="1"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    @error('turnover_duration')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
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
                    @error('room_status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Image Upload -->
                <div class="sm:col-span-2">
                    <label for="image" class="block mb-2 text-sm font-medium text-gray-900">Upload New Image
                        (Optional)</label>
                    <input type="file" wire:model="image" id="image" accept="image/png, image/jpeg"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">

                    @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <div wire:loading wire:target="image" class="mt-2 text-gray-600">Uploading image...</div>
                    <!-- Image Preview (Shows New Image if Selected, Otherwise Shows Current Image) -->
                    <div class="mt-2">
                        @if ($newImage)
                            <img src="{{ $newImage->temporaryUrl() }}"
                                class="w-32 h-32 object-cover rounded-lg shadow">
                        @elseif ($image)
                            <img src="{{ asset('storage/' . $image) }}"
                                class="w-32 h-32 object-cover rounded-lg shadow">
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-center space-y-2 mt-6">
                <x-button onclick="history.back()" type="button" class="!bg-gray-200 !text-black hover:!bg-gray-300 focus:!ring-2 focus:!ring-gray-400 focus:!outline-none">
                    Cancel
                </x-button>
                <x-button type="button" class="mt-3" @click="showConfirm = true">
                    Update Room
                </x-button>
            </div>
        </form>

        {{-- <x-confirmation-modal id="confirmUpdateModal" x-show="showConfirm" x-cloak @close-modal.window="showConfirm = false">
            <x-slot name="title">
                Confirm Update
            </x-slot>

            <x-slot name="content">
                <div class="text-md">
                    Are you sure you want to update this room?
                </div>
            </x-slot>

            <x-slot name="footer">
                <div class="flex justify-between w-full">
                    <x-button type="button" class="!bg-gray-400 hover:!bg-gray-500" @click="showConfirm = false">
                        Cancel
                    </x-button>
                    <x-button type="submit" @click="showConfirm = false">
                        Confirm
                    </x-button>
                </div>
            </x-slot>
        </x-confirmation-modal> --}}


    </div>
</div>
