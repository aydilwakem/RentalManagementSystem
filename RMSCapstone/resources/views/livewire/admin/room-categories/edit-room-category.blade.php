<div class="min-h-[550px] container mx-auto p-6 bg-white rounded-lg" x-data="{ showConfirm: false }">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Room Category') }}
        </h2>
    </x-slot>
    <!-- Form container -->
    <div class="shadow-lg rounded-lg p-6 max-w-2xl mx-auto border bg-white">
        <h2 class="mb-4 text-xl font-bold text-gray-900">Edit Category</h2>
        <form wire:submit.prevent="updateCategory">
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <!-- Name of Category -->
                <div class="sm:col-span-2">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Name</label>
                    <input type="text" wire:model="name" id="name" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                </div>
                <!-- Available Amenities (Dynamic) -->
                <div class="sm:col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-900">Amenities</label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach ($amenities as $amenity)
                            <div class="flex items-center">
                                <input type="checkbox" wire:model="selectedAmenities" value="{{ $amenity->id }}"
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded-sm focus:ring-blue-500">
                                <label class="ms-2 text-sm font-medium text-gray-900">
                                    {{ $amenity->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <!-- Description -->
                <div class="sm:col-span-2">
                    <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Description</label>
                    <textarea wire:model="description" id="description" rows="8"
                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 max-h-40 overflow-auto resize-none"
                        placeholder="Your description here"></textarea>

                </div>

                <!-- Image Upload -->
                <div class="sm:col-span-2">
                    <label for="image" class="block mb-2 text-sm font-medium text-gray-900">Upload Image</label>
                    <input accept="image/png, image/jpeg" type="file" wire:model="newImage" id="image"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">

                    @error('newImage')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <div wire:loading wire:target="newImage" class="mt-2 text-blue-600">
                        Uploading image...
                    </div>

                    <div class="mt-2">
                        @if ($newImage)
                            <img src="{{ $newImage->temporaryUrl() }}" class="w-32 h-32 object-cover rounded-lg shadow">
                        @elseif ($image)
                            <img src="{{ asset('storage/' . $image) }}" class="w-32 h-32 object-cover rounded-lg shadow">
                        @endif
                    </div>
                </div>

            </div>
            <!-- Submit Button -->
            <div class="flex justify-between items-center space-y-2 mt-6">
                <x-button onclick="history.back()" type="button"
                    class="!bg-gray-200 !text-black hover:!bg-gray-300 focus:!ring-2 focus:!ring-gray-400 focus:!outline-none">
                    Cancel
                </x-button>
                <x-button type="submit" wire:loading.attr="disabled" wire:target="newImage">
                    Save Changes
                </x-button>
            </div>
        </form>
    </div>
</div>