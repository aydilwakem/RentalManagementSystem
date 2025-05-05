    <!-- Form container -->
    <div class="mx-4 sm:mx-auto bg-white dark:bg-[#2A2A2A] rounded-2xl p-8">
        <h2 class="mb-4 text-xl font-bold text-gray-900 text-center">Edit Room</h2>

        <form wire:submit.prevent="">
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <!-- Room Name -->
                <div>
                    <label for="name_number" class="block mb-2 text-sm font-medium text-gray-900">Room Name</label>
                    <input type="text" wire:model="name_number" id="name_number" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    @error('name_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Room Category -->
                <div>
                    <label for="property_category_id" class="block mb-2 text-sm font-medium text-gray-900">Room
                        Category</label>
                    <select wire:model="property_category_id" id="property_category_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                        <option value="">Select Category</option>
                        @foreach ($roomCategories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('property_category_id')
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


                <!-- Max Occupancy Rules -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Maximum Occupancy Rules</h3>

                    <!-- Loop through the occupancy rules -->
                    @foreach ($occupancy_rules as $index => $rule)
                        <div class="mt-4 p-4 bg-white border border-gray-200 rounded-lg shadow-md">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Adults Field -->
                                <div>
                                    <label for="adults_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700">Adults</label>
                                    <input type="number" wire:model="occupancy_rules.{{ $index }}.adults"
                                        id="adults_{{ $index }}" min="0"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                                    @error("occupancy_rules.{$index}.adults")
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Kids Field -->
                                <div>
                                    <label for="kids_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700">Kids</label>
                                    <input type="number" wire:model="occupancy_rules.{{ $index }}.kids"
                                        id="kids_{{ $index }}" min="0"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                                    @error("occupancy_rules.{$index}.kids")
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Remove Rule Button -->
                            <div class="mt-4 flex justify-end">
                                <button type="button" wire:click="removeRule({{ $index }})"
                                    class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition-all duration-300">
                                    Remove Rule
                                </button>
                            </div>
                        </div>
                    @endforeach

                    <!-- Add Rule Button -->
                    <div class="mt-4 flex justify-start">
                        <button type="button" wire:click="addRule"
                            class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-all duration-300">
                            Add Rule
                        </button>
                    </div>
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
                <div>
                    <label for="property_status" class="block mb-2 text-sm font-medium text-gray-900">Room
                        Status</label>
                    <select wire:model="property_status" id="property_status"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                        <option value="available">Available</option>
                        <option value="booked">Booked</option>
                        <option value="out_of_service">Out of Service</option>
                    </select>
                    @error('property_status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Base Rate -->
                <div>
                    <label for="amount" class="block mb-2 text-sm font-medium text-gray-900">Base Rate</label>
                    <input type="amount" wire:model="amount" id="amount"
                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500"
                        placeholder="Enter base_rate">
                    @error('amount')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Available Amenities (Dynamic) -->
                <div class="sm:col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-900">Amenities</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                        @foreach ($features as $feature)
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


                <div class="space-y-4">
                    <!-- Image Upload -->
                    <div>
                        <label for="image" class="block mb-2 text-sm font-medium text-gray-900">Upload New Image
                            (Optional)</label>
                        <input type="file" wire:model="newImage" id="image" accept="image/png, image/jpeg"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">

                        @error('newImage')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <div wire:loading wire:target="newImage" class="flex items-center justify-center px-5 mt-4">
                            <svg class="animate-spin h-5 w-5 mr-2 text-green-700" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z"></path>
                            </svg>
                            <span>Uploading...</span>
                        </div>
                    </div>

                    <!-- Image Preview -->
                    <div>
                        @if ($newImage)
                            <!-- Show new uploaded image -->
                            <img src="{{ $newImage->temporaryUrl() }}"
                                class="mt-4 w-full h-48 object-contain rounded-lg shadow">
                        @elseif ($room->image)
                            <!-- Show existing image from storage -->
                            <img src="{{ asset('storage/' . $room->image) }}"
                                class="mt-4 w-full h-48 object-contain rounded-lg shadow">
                        @else
                            <!-- Show default image if no image exists -->
                            <img src="{{ asset('images/rms-default.png') }}"
                                class="mt-4 w-full h-48 object-contain rounded-lg shadow">
                        @endif
                    </div>
                </div>

            </div>

            <div class="flex justify-between items-center space-y-2 mt-6">
                <x-button onclick="history.back()" type="button"
                    class="!bg-gray-200 !text-black hover:!bg-gray-300 focus:!ring-2 focus:!ring-gray-400 focus:!outline-none">
                    Cancel
                </x-button>
                <x-button type="submit" wire:loading.attr="disabled" wire:target="newImage"
                    wire:click="confirmEdit({{ $room->id }})">
                    Save Changes
                </x-button>
            </div>
        </form>

        <!-- Edit Confirmation Modal -->
        <x-dialog-modal wire:model.live="confirmEditItem">
            <x-slot name="title">
                {{ __('Edit Room') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to save changes on this item?') }}
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('confirmEditItem', false)" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-button class="ms-3 bg-green text-white" wire:click="updateRoom({{ $room->id }})"
                    wire:loading.attr="disabled">
                    {{ __('Edit Room') }}
                </x-button>
            </x-slot>
        </x-dialog-modal>
    </div>
