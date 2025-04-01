<div class="min-h-[550px] container mx-auto p-6 bg-white rounded-lg">
    <div class="py-3 px-8 mx-auto max-w-2xl border rounded-lg bg-white shadow-md">
        <div class="mx-auto max-w-2xl lg:py-2s ">
            <h2 class="mb-6 text-xl font-bold text-gray-900 text-center">Edit House</h2>

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

                    <!-- Availability -->
                    <div>
                        <label for="availability"
                            class="block mb-2 text-sm font-medium text-gray-900">Availability</label>
                        <select wire:model.defer="availability" id="availability"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                            <option value="">-- Select Availability --</option>
                            <option value="available">Available</option>
                            <option value="unavailable">Unavailable</option>
                        </select>
                        @error('availability')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Monthly Rent -->
                    <div>
                        <label for="monthly_rent" class="block mb-2 text-sm font-medium text-gray-900">Monthly
                            Rent</label>
                        <input type="number" wire:model="monthly_rent" id="monthly_rent" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                            placeholder="Enter Monthly Rent">
                        @error('monthly_rent')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Address Fields -->
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
                        <label for="description" class="block mb-2 text-sm font-medium text-gray-900">House
                            Description</label>
                        <textarea wire:model="description" id="description" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 resize-none"
                            placeholder="Enter Property description"></textarea>
                        @error('description')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-between items-center space-y-2 mt-6">
                    <x-button onclick="history.back()" type="button"
                        class="!bg-gray-200 !text-black hover:!bg-gray-300 focus:!ring-2 focus:!ring-gray-400 focus:!outline-none">
                        Cancel
                    </x-button>
                    <x-button type="submit" wire:click="confirmEdit({{ $property->id }})"
                        wire:loading.attr="disabled">
                        Save Changes
                    </x-button>
                </div>

            </form>
        </div>


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

    </div>
</div>
