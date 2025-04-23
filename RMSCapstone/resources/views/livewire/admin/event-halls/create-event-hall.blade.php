<div class="border rounded-lg p-6 max-w-2xl mx-auto mb-8 mt-8">
    <div class="mx-auto max-w-2xl lg:py-2s">
        <h2 class="mb-4 text-xl font-bold text-gray-900 text-center">Add a new event hall</h2>

        <form wire:submit.prevent="">
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <!-- Name of Event Hall -->
                <div class="sm:col-span-2">
                    <label for="name_number" class="block mb-2 text-sm font-medium text-gray-900">Event Hall
                        Name</label>
                    <input type="text" wire:model="name_number" id="name_number"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                        placeholder="Type event category name" required>
                    @error('name_number')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Description -->
                <div class="sm:col-span-2">
                    <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Description</label>
                    <textarea wire:model="description" id="description" rows="8"
                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 resize-none"
                        placeholder="Your event category description here"></textarea>
                    @error('description')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Amount -->
                <div class="sm:col-span-2">
                    <label for="amount" class="block mb-2 text-sm font-medium text-gray-900">Amount</label>
                    <input type="number" wire:model="amount" id="amount" rows="8"
                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500"
                        placeholder="Event hall amount"></input>
                    @error('amount')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Capacity -->
                <div class="sm:col-span-2">
                    <label for="capacity" class="block mb-2 text-sm font-medium text-gray-900">Max Capacity</label>
                    <input type="number" wire:model="capacity" id="capacity" rows="8"
                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500"
                        placeholder="Event hall capacity"></input>
                    @error('capacity')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Extra Charge Per Hour -->
                <div class="sm:col-span-2">
                    <label for="extra_charge_per_hour" class="block mb-2 text-sm font-medium text-gray-900">Extra
                        Charge Per Hour</label>
                    <input type="number" wire:model="extra_charge_per_hour" id="extra_charge_per_hour" rows="8"
                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500"
                        placeholder="Event hall extra charge"></input>
                    @error('extra_charge_per_hour')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="property_status" class="block mb-2 text-sm font-medium text-gray-900">Event Hall
                        Status</label>
                    <select wire:model="property_status" id="property_status"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                        <option value="available">Available</option>
                        <option value="booked">Booked</option>
                        <option value="out_of_service">Out of Service</option>
                    </select>
                    @error('property_status')
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

            <div class="flex justify-between items-center space-y-2 mt-6">
                <x-button onclick="history.back()" type="button"
                    class="!bg-gray-200 !text-black hover:!bg-gray-300 focus:!ring-2 focus:!ring-gray-400 focus:!outline-none">
                    Cancel
                </x-button>
                <x-button type="submit" wire:loading.attr="disabled" wire:target="image" wire:click="confirmCreate">
                    Add Event Hall
                </x-button>
            </div>
        </form>
    </div>
    <!-- Create Confirmation Modal -->
    <x-dialog-modal wire:model.live="confirmCreateItem">
        <x-slot name="title">
            {{ __('Create Event Hall') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to add this item?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('confirmCreateItem', false)" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ms-3 bg-green text-white" wire:click="saveEventHall" wire:loading.attr="disabled">
                {{ __('Create Event Hall') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>

</div>