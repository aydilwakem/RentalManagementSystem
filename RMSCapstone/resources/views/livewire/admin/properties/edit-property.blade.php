<div class="mx-4 sm:mx-auto bg-white dark:bg-[#2A2A2A] rounded-2xl p-8">

    <h2 class="mb-4 text-xl font-bold text-gray-900 text-center">Edit House</h2>

    <form wire:submit.prevent="">
        <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

            <!-- Property Name -->
            <div class="sm:col-span-2">
                <label for="name_number" class="block mb-2 text-sm font-medium text-gray-900">House Name</label>
                <input type="text" wire:model="name_number" id="name_number" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                    placeholder="Enter House name">
                @error('name_number')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Availability Status -->
            <div>
                <label for="property_status" class="block mb-2 text-sm font-medium text-gray-900">Select
                    Availability</label>
                <select wire:model.defer="property_status" id="property_status"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    <option value="available">Available</option>
                    <option value="booked">Occupied</option>
                    <option value="out_of_service">Out of Service</option>
                </select>
                @error('property_status')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>


            <!-- Monthly Rent -->
            <div>
                <label for="amount" class="block mb-2 text-sm font-medium text-gray-900">Monthly Rent</label>
                <input type="number" wire:model="amount" id="amount" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                    placeholder="Enter Monthly Rent">
                @error('amount')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Capacity -->
            <div>
                <label for="capacity" class="block mb-2 text-sm font-medium text-gray-900">Capacity</label>
                <input type="number" wire:model="capacity" id="capacity"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                @error('capacity')
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

            <!-- House Number -->
            <div>
                <label for="house_number" class="block mb-2 text-sm font-medium text-gray-900">House
                    Number</label>
                <input type="text" wire:model="house_number" id="house_number" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                    placeholder="Enter House Number">
                @error('house_number')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Street -->
            <div>
                <label for="street" class="block mb-2 text-sm font-medium text-gray-900">Street</label>
                <input type="text" wire:model="street" id="street" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                    placeholder="Enter Street">
                @error('street')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Barangay -->
            <div>
                <label for="barangay" class="block mb-2 text-sm font-medium text-gray-900">Barangay</label>
                <input type="text" wire:model="barangay" id="barangay" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                    placeholder="Enter Barangay">
                @error('barangay')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- City/Municipality -->
            <div>
                <label for="city_municipality"
                    class="block mb-2 text-sm font-medium text-gray-900">City/Municipality</label>
                <input type="text" wire:model="city_municipality" id="city_municipality" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                    placeholder="Enter City/Municipality">
                @error('city_municipality')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Region -->
            <div>
                <label for="region" class="block mb-2 text-sm font-medium text-gray-900">Region</label>
                <input type="text" wire:model="region" id="region" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                    placeholder="Enter Region">
                @error('region')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Postal Code -->
            <div>
                <label for="postal_code" class="block mb-2 text-sm font-medium text-gray-900">Postal
                    Code</label>
                <input type="text" wire:model="postal_code" id="postal_code" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                    placeholder="Enter Postal Code">
                @error('postal_code')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Country -->
            <div>
                <label for="country" class="block mb-2 text-sm font-medium text-gray-900">Country</label>
                <input type="text" wire:model="country" id="country" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                    placeholder="Enter Country">
                @error('country')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Property Description -->
            <div class="sm:col-span-2">
                <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Property
                    Description</label>
                <textarea wire:model="description" id="description" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full h-20 p-2.5 resize-none"
                    placeholder="Enter Property description"></textarea>
                @error('description')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Available Amenities (Dynamic) -->
            <div class="sm:col-span-2">
                <label class="block mb-2 text-sm font-medium text-gray-900">Amenities</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                    @foreach ($house_features as $feature)
                    <div class="flex items-center">
                        <input type="checkbox" wire:model="selectedFeatures" value="{{ $feature->id }}"
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded-sm focus:ring-blue-500">
                        <label class="ms-2 text-sm font-medium text-gray-900">
                            {{ $feature->name }}
                        </label>
                    </div>
                    @endforeach
                </div>
                @error('selectedFeatures')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>


            <div class="space-y-4 col-span-2">
                <!-- Image Upload -->
                <div>
                    <label for="newImages" class="block mb-2 text-sm font-medium text-gray-900">Upload New Image
                        (Optional)</label>
                    <input type="file" wire:model="newImages" id="image" multiple accept="image/png, image/jpeg"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">

                    @error('newImage')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <div wire:loading wire:target="newImages" class="flex items-center justify-center px-5 mt-4">
                        <svg class="animate-spin h-5 w-5 mr-2 text-green-700" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z"></path>
                        </svg>
                        <span>Uploading...</span>
                    </div>
                </div>

                <!-- Image Previews -->
                <div class="col-span-2">
                    <div class="flex flex-wrap gap-4">
                        <!-- New uploaded image previews -->
                        @if ($newImages)
                        @foreach ($newImages as $image)
                        <img src="{{ $image->temporaryUrl() }}" class="w-52 h-40 object-cover rounded-lg shadow">
                        @endforeach
                        @endif

                        <!-- Existing stored image previews -->
                        @if ($storedImages)
                        @foreach ($storedImages as $index => $image)
                        <div class="relative shrink-0">
                            <img src="{{ asset('storage/' . $image) }}"
                                class="w-52 h-40 object-cover rounded-lg shadow">
                            <button type="button" wire:click="confirmImageDelete({{ $index }})" title="Delete Image"
                                class="absolute top-2 right-2 bg-gray-200 text-gray-500 rounded-full w-5 h-5 flex items-center justify-center text-sm font-semibold leading-none hover:bg-red-300 hover:text-red-700 transition">
                                ×
                            </button>
                        </div>
                        @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-between items-center space-y-2 mt-6">
            <x-button onclick="history.back()" type="button"
                class="!bg-gray-200 !text-black hover:!bg-gray-300 focus:!ring-2 focus:!ring-gray-400 focus:!outline-none">
                Cancel
            </x-button>
            <x-button type="submit" wire:click="confirmEdit({{ $property->id }})" wire:loading.attr="disabled">
                Save Changes
            </x-button>
        </div>

    </form>



    <!-- Edit Confirmation Modal -->
    <x-dialog-modal wire:model.live="confirmEditItem">
        <x-slot name="title">
            {{ __('Edit House') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to save changes on this item?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('confirmEditItem', false)" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ms-3 bg-green text-white" wire:click="updateProperty({{ $property->id }})"
                wire:loading.attr="disabled">
                {{ __('Edit House') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>

    <x-dialog-modal wire:model.live="confirmDeleteImage">
        <x-slot name="title">
            {{ __('Delete Image') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to delete this image?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('confirmDeleteImage', false)" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button wire:click="removeStoredImage" wire:loading.attr="disabled">
                {{ __('Delete') }}
            </x-danger-button>
        </x-slot>
    </x-dialog-modal>

</div>