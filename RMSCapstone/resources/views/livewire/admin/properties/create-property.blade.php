<div>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
            {{ __('Create Property') }}
        </h2>
    </x-slot>

    <!-- Body Container -->
    <div class="py-3">
        <div
            class="mx-auto max-w-7xl sm:px-6 lg:px-8 bg-white rounded-xl border shadow-md p-6 dark:bg-gray-700 dark:border-gray-600 dark:text-white">

            <div class="relative flex items-center mb-4">
                <!-- Title -->
                <h2 class="text-2xl font-bold text-gray-900 w-full text-center dark:text-white">Add New Property</h2>

                <!-- Back Button -->
                <button onclick="window.location.href='{{ route('admin.properties') }}'" wire:navigate
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute right-0 translate-y-[-12px]">
                    <span class="leading-none translate-y-[-3px]">&times;</span>
                </button>
            </div>

            <!-- Form container -->
            <form wire:submit.prevent="">
                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                    <!-- Property Name -->
                    <div class="sm:col-span-2">
                        <label for="name_number"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">House Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" wire:model="name_number" id="name_number" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                            placeholder="Ex. Grand Manor">
                        @error('name_number')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Availability Status -->
                    <div>
                        <label for="property_status"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Property
                            Status <span class="text-red-500">*</span></label>
                        <select wire:model="property_status" id="property_status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                            <option value="available">Available</option>
                            <option value="out_of_service">Out of Service</option>
                        </select>
                        @error('property_status')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>


                    <!-- Monthly Rent -->
                    <div>
                        <label for="amount"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Monthly Rent <span
                                class="text-red-500">*</span></label>
                        <input type="number" wire:model="amount" id="amount" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-green-600 focus:border-green-600
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                            placeholder="Ex. 8,500.00" onwheel="this.blur()">
                        @error('amount')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Capacity -->
                    {{-- <div>
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

                        <!-- Region -->
                        <div>
                            <label for="region"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Region <span
                                    class="text-red-500">*</span></label>
                            <select id="region" wire:model.live="selectedRegion" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                <option value="">Select Region</option>
                                @foreach ($regions as $region)
                                <option value="{{ $region->PSGC_REG_CODE }}">
                                    {{ $region->PSGC_REG_DESC }}
                                </option>
                                @endforeach
                            </select>
                            @error('region')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Province -->
                        <div>
                            <label for="province"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Province <span
                                    class="text-red-500">*</span></label>
                            <select id="province" wire:model.live="selectedProvince" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                <option value="">Select Province</option>
                                @foreach ($provinces as $province)
                                <option value="{{ $province->PSGC_PROV_CODE }}">
                                    {{ $province->PSGC_PROV_DESC }}
                                </option>
                                @endforeach
                            </select>
                            @error('province')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- City/Municipality -->
                        <div>
                            <label for="city_municipality"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">City/Municipality
                                <span class="text-red-500">*</span></label>
                            <select id="city_municipality" wire:model.live="selectedMunicipality" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                <option value="">Select City/Municipality</option>
                                @foreach ($municipalities as $municipality)
                                <option value="{{ $municipality->PSGC_MUNC_CODE }}">
                                    {{ $municipality->PSGC_MUNC_DESC }}
                                </option>
                                @endforeach
                            </select>
                            @error('city_municipality')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Barangay -->
                        <div>
                            <label for="barangay"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Barangay <span
                                    class="text-red-500">*</span></label>
                            <select id="city_municipality" wire:model.live="selectedBarangay" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                <option value="">Select Barangay</option>
                                @foreach ($barangays as $barangay)
                                <option value="{{ $barangay->PSGC_BRGY_CODE }}">
                                    {{ $barangay->PSGC_BRGY_DESC }}
                                </option>
                                @endforeach
                            </select>
                            @error('barangay')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- House Number -->
                        <div>
                            <label for="house_number"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">House
                                Number <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="house_number" id="house_number" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-green-600 focus:border-green-600
                                dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                                placeholder="Ex. 123, Blk 1 Lot 5">
                            @error('house_number')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Street -->
                        <div>
                            <label for="street"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Street <span
                                    class="text-red-500">*</span></label>
                            <input type="text" wire:model="street" id="street" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-green-600 focus:border-green-600
                                dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                                placeholder="Ex. Sampaguita Street">
                            @error('street')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Country -->
                        <div>
                            <label for="country"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Country <span
                                    class="text-red-500">*</span></label>
                            <input type="text" wire:model="country" id="country" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-green-600 focus:border-green-600
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                                placeholder="Ex. Philippines">
                            @error('country')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Postal Code -->
                        <div>
                            <label for="postal_code"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Postal
                                Code <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="postal_code" id="postal_code" required readonly class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-green-600 focus:border-green-600
                                dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                                placeholder="Ex. 1630">
                            @error('postal_code')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Property Description -->
                        <div>
                            <label for="description"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Property
                                Description</label>
                            <input type="text" wire:model="description" id="description" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 resize-none
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                                placeholder="Spacious 3-bedroom, 2-bath house with garden access and parking" />
                            @error('description')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>


                    <!-- Available Amenities (Dynamic) -->
                    <div class="sm:col-span-2">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Amenities</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                            @foreach ($features as $feature)
                            <div class="flex items-center">
                                <input type="checkbox" wire:model="selectedFeatures" value="{{ $feature->id }}"
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded-sm focus:ring-green-600 focus:border-green-600">
                                <label class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                                    {{ $feature->name }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                        @error('selectedFeatures')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Image Upload -->
                    <div class="mb-4 col-span-2">
                        <label for="images"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Upload House
                            Image(s)</label>
                        <div class="flex flex-wrap gap-4">
                            @if ($images && count($images) > 0)
                            @foreach ($images as $index => $image)
                            <div class="relative shrink-0">
                                <img src="{{ $image->temporaryUrl() }}"
                                    class="w-52 h-40 object-cover rounded-md shadow-sm" alt="Image Preview">
                                <button type="button" wire:click="removeImage({{ $index }})"
                                    class="absolute top-2 right-2 bg-gray-200 text-gray-500 rounded-full w-5 h-5 flex items-center justify-center text-sm font-semibold leading-none hover:bg-red-300 hover:text-red-700 transition">
                                    ×
                                </button>
                                @if ($loop->first)
                                <span
                                    class="absolute bottom-0 left-0 bg-black bg-opacity-50 text-white text-xs rounded-sm px-1">Main
                                    Image</span>
                                @endif
                            </div>
                            @endforeach
                            <label for="imageInput" class="cursor-pointer shrink-0" wire:loading.remove
                                wire:target="images">
                                <div
                                    class="w-52 h-40 border-2 border-dashed border-gray-400 rounded-md flex items-center justify-center text-gray-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                            </label>
                            @else
                            <label for="imageInput" class="cursor-pointer shrink-0" wire:loading.remove
                                wire:target="images">
                                <div
                                    class="w-52 h-40 border-2 border-dashed border-gray-400 rounded-md flex flex-col items-center justify-center text-gray-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    <span class="text-xs">Add image</span>
                                </div>
                            </label>
                            @endif

                            <input multiple type="file" wire:model="images" id="imageInput"
                                accept="image/png, image/jpeg" class="hidden" @if (!$images || count($images) < 5)
                                @endif>

                            @error('images.*')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div wire:loading wire:target="images" class="flex items-center justify-start mt-2">
                            <svg class="animate-spin h-5 w-5 mr-2 text-green-700" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4">
                                </circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z"></path>
                            </svg>
                            <span>Uploading...</span>
                        </div>
                    </div>

                </div>

                <!-- Submit Button -->
                <div class="flex justify-between items-center space-y-2 mt-6">
                    <x-ghost-button onclick="history.back()" type="button">
                        Cancel
                    </x-ghost-button>
                    <x-button type="submit" wire:click="confirmCreate" wire:loading.attr="disabled">
                        Create House
                    </x-button>
                </div>
            </form>

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
    </div>
</div>