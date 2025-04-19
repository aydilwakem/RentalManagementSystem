<!-- Main container -->
<div class="min-h-[550px] container mx-auto p-6 bg-white rounded-lg" x-data="{ showConfirm: false }">
    <!-- Form container -->
    <div class="shadow-lg rounded-lg p-6 max-w-2xl mx-auto border bg-bwhite">
        <h2 class="mb-4 text-xl font-bold text-gray-900 text-center">Edit Room</h2>

        <form wire:submit.prevent="">
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <!-- Room Name -->
                <div class="sm:col-span-2">
                    <label for="name_number" class="block mb-2 text-sm font-medium text-gray-900">Room Name</label>
                    <input type="text" wire:model="name_number" id="name_number" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    @error('name_number')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Room Category -->
                <div class="sm:col-span-2">
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
                    <div class="grid grid-cols-2 gap-2">
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


                <!-- Image Upload -->
                <div class="sm:col-span-2">
                    <label for="image" class="block mb-2 text-sm font-medium text-gray-900">Upload New Image
                        (Optional)</label>
                    <input type="file" wire:model="newImage" id="image" accept="image/png, image/jpeg"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">

                    @error('newImage')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <div wire:loading wire:target="newImage" class="mt-2 text-gray-600">Uploading image...</div>

                    <!-- Image Preview -->
                    <div class="mt-2">
                        @if ($newImage)
                        <!-- Show new uploaded image -->
                        <img src="{{ $newImage->temporaryUrl() }}" class="w-32 h-32 object-cover rounded-lg shadow">
                        @elseif ($room->image)
                        <!-- Show existing image from storage -->
                        <img src="{{ asset('storage/' . $room->image) }}"
                            class="w-32 h-32 object-cover rounded-lg shadow">
                        @else
                        <!-- Show default image if no image exists -->
                        <img src="{{ asset('images/rms-default.png') }}"
                            class="w-32 h-32 object-cover rounded-lg shadow">
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
    </div>
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