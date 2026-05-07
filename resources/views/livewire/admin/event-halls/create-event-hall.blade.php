<div>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white mb-1">
            {{ __('Create Event Hall') }}
        </h2>
        <!-- Navigation -->
        <x-breadcrumbs :items="[
            ['label' => 'Event Halls', 'url' => route('admin.event-halls')],
            ['label' => 'Create Event Hall', 'url' => route('admin.create-event-hall')],
        ]" />
    </x-slot>

    <!-- Body Container -->
    <div class="py-3">
        <div
            class="mx-auto max-w-full sm:px-6 lg:px-8 bg-white rounded-xl border shadow-md p-6 dark:bg-gray-700 dark:border-gray-600 dark:text-white">

            <div class="relative flex items-center mb-4">
                <!-- Title -->
                <h2 class="text-2xl font-bold text-gray-900 w-full text-center dark:text-white">Add New Event Hall</h2>

                <!-- Back Button -->
                <button onclick="window.location.href='{{ route('admin.event-halls') }}'" wire:navigate
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute right-0 translate-y-[-12px]">
                    <span class="leading-none translate-y-[-3px]">&times;</span>
                </button>
            </div>

            <!-- Form container -->
            <form wire:submit.prevent="">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-6">

                    <!-- Name of Event Hall -->
                    <div class="md:col-span-2">
                        <label for="name_number"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Event Hall Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="name_number" id="name_number"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                            placeholder="Ex. Amity Hall" required>
                        @error('name_number')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Description</label>
                        <textarea wire:model="description" id="description" rows="3"
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-green-600 focus:border-green-600 resize-none
                dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                            placeholder="Ex. Enjoy our spacious hall with stunning outdoor access."></textarea>
                        @error('description')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Amount -->
                    <div>
                        <label for="amount" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Base Rate (first 4 hours) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" wire:model="amount" id="amount"
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-green-600 focus:border-green-600
                dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                            placeholder="Ex. 45,000.00">
                        @error('amount')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Capacity -->
                    <div>
                        <label for="capacity" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Maximum Capacity <span class="text-red-500">*</span>
                        </label>
                        <input type="number" wire:model="capacity" id="capacity"
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-green-600 focus:border-green-600
                dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                            placeholder="Ex. 100 Pax">
                        @error('capacity')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Extra Charge Per Hour -->
                    <div>
                        <label for="extra_charge_per_hour"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Extra Charge Per Hour <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="extra_charge_per_hour" id="extra_charge_per_hour"
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-green-600 focus:border-green-600
                dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                            placeholder="Ex. 7,000.00">
                        @error('extra_charge_per_hour')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="property_status"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Event Hall Status <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="property_status" id="property_status"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                            <option value="available">Available</option>
                            <option value="out_of_service">Out of Service</option>
                        </select>
                        @error('property_status')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Amenities -->
                    <div class="md:col-span-2">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Amenities</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            @foreach ($features as $feature)
                                <div class="flex items-center">
                                    <input type="checkbox" wire:model="selectedFeatures" value="{{ $feature->id }}"
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded-sm focus:ring-blue-500">
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
                    <div class="mb-4 md:col-span-2">
                        <label for="newImageInput"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Event Hall
                            Image(s)</label>
                        <div class="flex flex-wrap gap-4" wire:sortable="reorderImages">
                            {{-- Combine both arrays for display and sorting --}}
                            @php
                                $displayImages = array_merge($uploadedImagePreviews, $persistedImagePaths);
                            @endphp

                            @if ($displayImages && count($displayImages) > 0)
                                @foreach ($displayImages as $index => $image)
                                    <!-- Image Preview -->
                                    <div class="relative shrink-0" wire:sortable.item="{{ $index }}"
                                        wire:key="image-{{ $index }}">
                                        @if (is_object($image) && method_exists($image, 'temporaryUrl'))
                                            <img src="{{ $image->temporaryUrl() }}"
                                                class="w-52 h-40 object-cover rounded-md shadow-sm" alt="Image Preview">
                                        @else
                                            <img src="{{ asset('storage/' . $image) }}"
                                                class="w-52 h-40 object-cover rounded-md shadow-sm" alt="Stored Image">
                                        @endif

                                        <!-- Remove Image -->
                                        <button type="button" wire:click="removeImage({{ $index }})"
                                            class="absolute top-2 right-2 bg-gray-200 text-gray-500 rounded-full w-5 h-5 flex items-center justify-center text-sm font-semibold leading-none hover:bg-red-300 hover:text-red-700 transition">
                                            ×
                                        </button>

                                        <!-- Main Image Title -->
                                        @if ($loop->first)
                                            <span
                                                class="absolute bottom-0 left-0 bg-black bg-opacity-50 text-white text-xs rounded-sm px-1">
                                                Main Image
                                            </span>
                                        @endif
                                    </div>
                                @endforeach

                                <!-- Add more images placeholder box -->
                                <label for="newImageInput" class="cursor-pointer shrink-0" wire:loading.remove
                                    wire:target="newImages">
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
                                <!-- Upload image placeholder box -->
                                <label for="newImageInput" class="cursor-pointer shrink-0" wire:loading.remove
                                    wire:target="newImages">
                                    <div
                                        class="w-52 h-40 border-2 border-dashed border-gray-400 rounded-md flex flex-col items-center justify-center text-gray-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        <span class="text-xs">Add image</span>
                                    </div>
                                </label>
                            @endif

                            <!-- Hidden file input -->
                            <input multiple type="file" wire:model="newImages" id="newImageInput"
                                accept="image/png, image/jpeg" class="hidden">

                            @error('newImages.*')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Spinner loading indicator -->
                        <div wire:loading wire:target="newImages" class="flex items-center justify-start mt-2">
                            <svg class="animate-spin h-5 w-5 mr-2 text-green-700" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                                </path>
                            </svg>
                            <span>Uploading...</span>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="mt-6 flex justify-between gap-4 md:col-span-2">
                        <x-ghost-button onclick="history.back()" type="button">
                            Cancel
                        </x-ghost-button>
                        <x-button type="submit" wire:loading.attr="disabled" wire:target="image"
                            wire:click="confirmCreate">
                            Create Event Hall
                        </x-button>
                    </div>

                </div>
            </form>


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

                    <x-button class="ms-3 bg-green text-white" wire:click="saveEventHall"
                        wire:loading.attr="disabled">
                        {{ __('Create Event Hall') }}
                    </x-button>
                </x-slot>
            </x-dialog-modal>

        </div>
    </div>
</div>
