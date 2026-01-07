<div>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white mb-1">
            {{ __('Edit Event Hall') }}
        </h2>
        <!-- Navigation -->
        <x-breadcrumbs :items="[
            ['label' => 'Event Halls', 'url' => route('admin.event-halls')],
            ['label' => 'View Event Hall', 'url' => route('admin.view-event-hall', ['eventHall' => $eventHall->id])],
            ['label' => 'Edit Event Hall', 'url' => route('admin.edit-event-hall', ['eventHall' => $eventHall->id])],
        ]" />
    </x-slot>

    <!-- Body Container -->
    <div class="py-3">
        <div
            class="mx-auto max-w-7xl sm:px-6 lg:px-8 bg-white rounded-xl border shadow-md p-6 dark:bg-gray-700 dark:border-gray-600 dark:text-white">

            <div class="relative flex items-center mb-4">
                <!-- Title -->
                <h2 class="text-2xl font-bold text-gray-900 w-full text-center dark:text-white">Edit Event Hall</h2>

                <!-- Back Button -->
                <button onclick="history.back()"
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
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Event Hall
                            Name <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="name_number" id="name_number"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                            dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
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
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-green-600 focus:border-green-600 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white resize-none"
                            placeholder="Ex. Enjoy our spacious hall with stunning outdoor access."></textarea>
                        @error('description')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Amount -->
                    <div>
                        <label for="amount"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Base Rate (first 4
                            hours)
                            <span class="text-red-500">*</span></label>
                        <input type="number" wire:model="amount" id="amount" rows="8"
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-green-600 focus:border-green-600 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                            placeholder="Ex. 45,000.00"></input>
                        @error('amount')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Capacity -->
                    <div>
                        <label for="capacity"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Maximum
                            Capacity <span class="text-red-500">*</span></label>
                        <input type="number" wire:model="capacity" id="capacity" rows="8"
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-green-600 focus:border-green-600 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                            placeholder="Ex. 100 Pax"></input>
                        @error('capacity')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Extra Charge Per Hour -->
                    <div>
                        <label for="extra_charge_per_hour"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Extra
                            Charge Per Hour <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="extra_charge_per_hour" id="extra_charge_per_hour"
                            rows="8"
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-green-600 focus:border-green-600 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                            placeholder="Ex. 7,000.00"></input>
                        @error('extra_charge_per_hour')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Event Hall Status -->
                    <div>
                        <label for="property_status"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Event Hall
                            Status <span class="text-red-500">*</span></label>
                        <select wire:model="property_status" id="property_status"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                            dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                            <option value="available">Available</option>
                            <option value="out_of_service">Out of Service</option>
                        </select>
                        @error('property_status')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Available Amenities (Dynamic) -->
                    <div class="md:col-span-2">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Amenities</label>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach ($inclusions as $inclusion)
                                <div class="flex items-center">
                                    <input type="checkbox" wire:model="selectedFeatures" value="{{ $inclusion->id }}"
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded-sm focus:ring-blue-500">
                                    <label class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                                        {{ $inclusion->name }}
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
                        <!-- Section Title -->
                        <label for="newImageInput"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Property
                            Image(s)</label>

                        <div class="flex flex-wrap gap-4" wire:sortable="reorderImages">
                            @if ($displayImages && count($displayImages) > 0)
                                @foreach ($displayImages as $image)
                                    <div class="relative shrink-0" wire:sortable.item="{{ $image['id'] }}"
                                        wire:key="image-{{ $image['id'] }}">

                                        <!-- Image Preview -->
                                        @if (isset($image['object']) && method_exists($image['object'], 'temporaryUrl'))
                                            <img src="{{ $image['object']->temporaryUrl() }}"
                                                class="w-52 h-40 object-cover rounded-md shadow-sm"
                                                alt="Image Preview">
                                        @elseif (isset($image['path']))
                                            <img src="{{ asset('storage/' . $image['path']) }}"
                                                class="w-52 h-40 object-cover rounded-md shadow-sm"
                                                alt="Stored Image">
                                        @endif

                                        <!-- Remove Image Button -->
                                        <button type="button" wire:click="confirmImageDelete('{{ $image['id'] }}')"
                                            title="Delete Image"
                                            class="absolute top-2 right-2 bg-gray-200 text-gray-500 rounded-full w-5 h-5 flex items-center justify-center text-sm font-semibold leading-none hover:bg-red-300 hover:text-red-700 transition">
                                            ×
                                        </button>

                                        <!-- Main Image Tite -->
                                        @if ($loop->first)
                                            <span
                                                class="absolute bottom-0 left-0 bg-black bg-opacity-50 text-white text-xs rounded-sm px-1">
                                                Main Image
                                            </span>
                                        @endif
                                    </div>
                                @endforeach

                                <!-- Add Image Placeholder -->
                                <label for="newImageInput" class="cursor-pointer shrink-0" wire:loading.remove
                                    wire:target="newImages">
                                    <div
                                        class="w-52 h-40 border-2 border-dashed border-gray-400 rounded-md flex items-center justify-center text-gray-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </div>
                                </label>
                            @else
                                <!-- Upload Image Placeholder -->
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

                            <!-- Hidden File Input -->
                            <input multiple type="file" wire:model="newImages" id="newImageInput"
                                accept="image/png, image/jpeg" class="hidden">

                            @error('newImages.*')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Spinner Loading Indicator -->
                        <div wire:loading wire:target="newImages" class="flex items-center justify-start mt-2">
                            <svg class="animate-spin h-5 w-5 mr-2 text-green-700 dark:text-green-300"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z"></path>
                            </svg>
                            <span class="dark:text-gray-200">Uploading...</span>
                        </div>
                    </div>

                    <!-- Submit & Cancel Buttons at Bottom -->
                    <div class="mt-6 flex justify-between gap-4 md:col-span-2">
                        <x-ghost-button onclick="history.back()" type="button">
                            Cancel
                        </x-ghost-button>
                        <x-button type="submit" wire:loading.attr="disabled" wire:target="image"
                            wire:click="confirmEdit({{ $eventHall->id }})">
                            Save Changes
                        </x-button>
                    </div>

            </form>
        </div>
        <!-- Edit Confirmation Modal -->
        <x-dialog-modal wire:model.live="confirmEditItem">
            <x-slot name="title">
                {{ __('Edit Event Hall') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to save changes to this item?') }}
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('confirmEditItem', false)" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-button class="ms-3 bg-green text-white" wire:click="updateEventHall({{ $eventHall->id }})"
                    wire:loading.attr="disabled">
                    {{ __('Save Changes') }}
                </x-button>
            </x-slot>
        </x-dialog-modal>

        <!-- Remove Image Confirmation Modal -->
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
</div>
