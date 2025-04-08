<div class="border rounded-lg p-6 max-w-2xl mx-auto mb-8 mt-8">
    <div class="mx-auto max-w-2xl lg:py-2s">

        <h2 class="mb-6 text-xl font-bold text-gray-900 text-center">Add new House</h2>

        <form wire:submit.prevent="">
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                <!-- Property Name -->
                <div class="sm:col-span-2">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900">House Name</label>
                    <input type="text" wire:model="name" id="name" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                        placeholder="Enter Property name">
                    @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- House Category -->
                <div>
                    <label for="house_category_id" class="block mb-2 text-sm font-medium text-gray-900">House
                        Category</label>
                    <select wire:model="house_category_id" id="house_category_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                        <option value="">Select Category</option>
                        @foreach ($houseCategories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('house_category_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Availability Status -->
                <div>
                    <label for="availability" class="block mb-2 text-sm font-medium text-gray-900">Availability</label>
                    <select wire:model.defer="availability" id="availability"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                        <option value="">-- Select Availability -- </option>
                        <option value="available">Available</option>
                        <option value="unavailable">Unavailable</option>
                    </select>
                    @error('availability')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>


                <!-- Monthly Rent -->
                <div>
                    <label for="monthly_rent" class="block mb-2 text-sm font-medium text-gray-900">Monthly Rent</label>
                    <input type="number" wire:model="monthly_rent" id="monthly_rent" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                        placeholder="Enter Monthly Rent">
                    @error('monthly_rent')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- House Number -->
                <div>
                    <label for="house_number" class="block mb-2 text-sm font-medium text-gray-900">House Number</label>
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

                <!-- Province -->
                <div>
                    <label for="province" class="block mb-2 text-sm font-medium text-gray-900">Province</label>
                    <input type="text" wire:model="province" id="province" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                        placeholder="Enter Province">
                    @error('province')
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
                    <label for="postal_code" class="block mb-2 text-sm font-medium text-gray-900">Postal Code</label>
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
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 resize-none"
                        placeholder="Enter Property description"></textarea>
                    @error('description')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Image Upload -->
                <div class="sm:col-span-2">
                    <label for="image" class="block mb-2 text-sm font-medium text-gray-900">Upload Image</label>
                    <input accept="image/png, image/jpeg" type="file" wire:model="image" id="image"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">

                    <!-- Error Message -->
                    @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <!-- Loading Indicator (Shows when file is being uploaded) -->
                    <div wire:loading wire:target="image" class="mt-2 text-blue-600">
                        Uploading image...
                    </div>

                    <!-- Image Preview (Only if an image is selected and processed) -->
                    @if ($image && method_exists($image, 'temporaryUrl'))
                        <div class="mt-2">
                            <img src="{{ $image->temporaryUrl() }}" class="w-32 h-32 object-cover rounded-lg shadow">
                        </div>
                    @endif
                </div>


            </div>

            <!-- Submit Button -->
            <div class="flex justify-between items-center space-y-2 mt-6">
                <x-button onclick="history.back()" type="button"
                    class="!bg-gray-200 !text-black hover:!bg-gray-300 focus:!ring-2 focus:!ring-gray-400 focus:!outline-none">
                    Cancel
                </x-button>
                <x-button type="submit" wire:click="confirmCreate" wire:loading.attr="disabled">
                    Add House
                </x-button>
            </div>
        </form>
    </div>
    <!-- Create Confirmation Modal -->
    <x-dialog-modal wire:model.live="confirmCreateItem">
        <x-slot name="title">
            {{ __('Create House') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to add this item?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('confirmCreateItem', false)" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ms-3 bg-green text-white" wire:click="saveProperty" wire:loading.attr="disabled">
                {{ __('Create House') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>