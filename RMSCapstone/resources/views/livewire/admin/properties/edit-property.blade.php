<div>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Property') }}
        </h2>
    </x-slot>

    <!-- Body Container -->
    <div class="py-3">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 bg-white rounded-xl border shadow-md p-6">

            <div class="relative flex items-center mb-4">
                <!-- Title -->
                <h2 class="text-2xl font-bold text-gray-900 w-full text-center">Edit Property Details</h2>

                <!-- Back Button -->
                <button onclick="history.back()" wire:navigate
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute right-0 translate-y-[-12px]">
                    <span class="leading-none translate-y-[-3px]">&times;</span>
                </button>
            </div>

            <form wire:submit.prevent="">
                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                    <!-- Property Name -->
                    <div class="sm:col-span-2">
                        <label for="name_number" class="block mb-2 text-sm font-medium text-gray-900">House Name <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="name_number" id="name_number" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5"
                            placeholder="Ex. Grand Manor">
                        @error('name_number')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Availability Status -->
                    <div>
                        <label for="property_status" class="block mb-2 text-sm font-medium text-gray-900">Select
                            Availability <span class="text-red-500">*</span></label>
                        <select wire:model.defer="property_status" id="property_status"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5">
                            <option value="available">Available</option>
                            <option value="out_of_service">Out of Service</option>
                        </select>
                        @error('property_status')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>


                    <!-- Monthly Rent -->
                    <div>
                        <label for="amount" class="block mb-2 text-sm font-medium text-gray-900">Monthly Rent <span class="text-red-500">*</span></label>
                        <input type="number" wire:model="amount" id="amount" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-green-600 focus:border-green-600"
                            placeholder="Ex. 8,500.00">
                        @error('amount')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- <!-- Capacity -->
            <div>
                <label for="capacity" class="block mb-2 text-sm font-medium text-gray-900">Capacity</label>
                <input type="number" wire:model="capacity" id="capacity"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5">
                @error('capacity')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div> --}}

                    <!-- Max Adults -->
                    {{-- <div>
                <label for="max_adults" class="block mb-2 text-sm font-medium text-gray-900">Max Adults</label>
                <input type="number" wire:model="max_adults" id="max_adults" min="0"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5">
                @error('max_adults')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div> --}}

                    <!-- Max Kids -->
                    {{-- <div>
                <label for="max_kids" class="block mb-2 text-sm font-medium text-gray-900">Max Kids</label>
                <input type="number" wire:model="max_kids" id="max_kids" min="0"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5">
                @error('max_kids')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div> --}}

                    <!-- House Details -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 col-span-2">

                        <!-- House Number -->
                        <div>
                            <label for="house_number" class="block mb-2 text-sm font-medium text-gray-900">House
                                Number <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="house_number" id="house_number" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-green-600 focus:border-green-600"
                                placeholder="Ex. 123, Blk 1 Lot 5">
                            @error('house_number')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Street -->
                        <div>
                            <label for="street" class="block mb-2 text-sm font-medium text-gray-900">Street <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="street" id="street" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-green-600 focus:border-green-600"
                                placeholder="Ex. Sampaguita Street">
                            @error('street')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Barangay -->
                        <div>
                            <label for="barangay" class="block mb-2 text-sm font-medium text-gray-900">Barangay <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="barangay" id="barangay" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-green-600 focus:border-green-600"
                                placeholder="Ex. Brgy. San Antonio">
                            @error('barangay')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- City/Municipality -->
                        <div>
                            <label for="city_municipality"
                                class="block mb-2 text-sm font-medium text-gray-900">City/Municipality <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="city_municipality" id="city_municipality" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-green-600 focus:border-green-600"
                                placeholder="Ex. Taguig City">
                            @error('city_municipality')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Region -->
                        <div>
                            <label for="region" class="block mb-2 text-sm font-medium text-gray-900">Region <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="region" id="region" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-green-600 focus:border-green-600"
                                placeholder="Ex. NCR">
                            @error('region')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Postal Code -->
                        <div>
                            <label for="postal_code" class="block mb-2 text-sm font-medium text-gray-900">Postal
                                Code <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="postal_code" id="postal_code" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-green-600 focus:border-green-600"
                                placeholder="Ex. 1630">
                            @error('postal_code')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Country -->
                    <div>
                        <label for="country" class="block mb-2 text-sm font-medium text-gray-900">Country <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="country" id="country" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-green-600 focus:border-green-600"
                            placeholder="Ex. Philippines">
                        @error('country')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Property Description -->
                    <div>
                        <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Property
                            Description</label>
                        <input type="text" wire:model="description" id="description" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 resize-none"
                            placeholder="Spacious 3-bedroom, 2-bath house with garden access and parking"/>
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
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded-sm focus:ring-green-600 focus:border-green-600">
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
                            <label for="newImages" class="block mb-2 text-sm font-medium text-gray-900">Upload New
                                Image
                                (Optional)</label>
                            <input type="file" wire:model="newImages" id="image" multiple
                                accept="image/png, image/jpeg"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5">

                            @error('newImage')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            <div wire:loading wire:target="newImages"
                                class="flex items-center justify-center px-5 mt-4">
                                <svg class="animate-spin h-5 w-5 mr-2 text-green-700" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4">
                                    </circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                                    </path>
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
                                        <img src="{{ $image->temporaryUrl() }}"
                                            class="w-52 h-40 object-cover rounded-lg shadow">
                                    @endforeach
                                @endif

                                <!-- Existing stored image previews -->
                                @if ($storedImages)
                                    @foreach ($storedImages as $index => $image)
                                        <div class="relative shrink-0">
                                            <img src="{{ asset('storage/' . $image) }}"
                                                class="w-52 h-40 object-cover rounded-lg shadow">
                                            <button type="button"
                                                wire:click="confirmImageDelete({{ $index }})"
                                                title="Delete Image"
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
                    <x-ghost-button onclick="history.back()" type="button">
                        Cancel
                    </x-ghost-button>
                    <x-button type="submit" wire:click="confirmEdit({{ $property->id }})"
                        wire:loading.attr="disabled">
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
                    {{ __('Are you sure you want to save changes to this item?') }}
                </x-slot>

                <x-slot name="footer">
                    <x-secondary-button wire:click="$set('confirmEditItem', false)" wire:loading.attr="disabled">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-button class="ms-3 bg-green text-white" wire:click="updateProperty({{ $property->id }})"
                        wire:loading.attr="disabled">
                        {{ __('Save Changes') }}
                    </x-button>
                </x-slot>
            </x-dialog-modal>

            <x-dialog-modal wire:model.live="confirmDeleteImage" type="danger">
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
