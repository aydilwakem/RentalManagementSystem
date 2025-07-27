<div>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white mb-1">
            {{ __('Edit Room') }}
        </h2>
        <!-- Navigation -->
        <x-breadcrumbs :items="[
        ['label' => 'Rooms', 'url' => route('admin.rooms')],
        ['label' => 'View Room', 'url' => route('admin.view-room', ['room' => $room->id])],
        ['label' => 'Edit Room', 'url' => route('admin.edit-room', ['room' => $room->id])],
    ]" />
    </x-slot>

    <!-- Body Container -->
    <div class="py-3">
        <div
            class="mx-auto max-w-7xl sm:px-6 lg:px-8 bg-white rounded-xl border shadow-md p-6 dark:bg-gray-700 dark:border-gray-600">

            <div class="relative flex items-center mb-4">
                <!-- Title -->
                <h2 class="text-2xl font-bold text-gray-900 w-full text-center dark:text-white">Edit Room</h2>

                <!-- Back Button -->
                <button onclick="history.back()"
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute right-0 translate-y-[-12px]">
                    <span class="leading-none translate-y-[-3px]">&times;</span>
                </button>
            </div>

            <!-- Form container -->
            <form wire:submit.prevent="">
                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                    <!-- Room Name -->
                    <div>
                        <label for="name_number"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Room Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" wire:model="name_number" id="name_number" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                            dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                            placeholder="Ex. Solah, Mercy">
                        @error('name_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Room Category -->
                    <div>
                        <label for="property_category_id"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Room
                            Category <span class="text-red-500">*</span></label>
                        <select wire:model="property_category_id" id="property_category_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                            dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                            <option value="">Select Category</option>
                            @foreach ($roomCategories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('property_category_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Guest Inputs -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 col-span-2">

                        <!-- Ideal Guest -->
                        <div>
                            <label for="ideal_guest"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Ideal
                                Guests <span class="text-red-500">*</span></label>
                            <input type="number" wire:model="ideal_guest" id="ideal_guest" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                                dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                placeholder="Ex. 2" onwheel="this.blur()" />
                            @error('ideal_guest')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Occupancy Type -->
                        <div>
                            <label for="occupancy_type"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Occupancy
                                Type <span class="text-red-500">*</span></label>
                            <select id="occupancy_type" wire:model.live="occupancy_type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                                <option value="" selected>Select Occupancy Type</option>
                                <option value="combinations">Mixed Guest Composition (Ex. 2 Adults + 2 Kids)</option>
                                <option value="whole_number">Total No. of Guests (Ex. 4 Guests)</option>
                            </select>

                            @error('occupancy_type')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Max Occupancy Rules (Only if 'combinations') -->
                        @if ($occupancy_type === 'combinations')
                            <div class="col-span-2 border rounded-md shadow-sm bg-gray-50 p-4">
                                <h3 class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Occupancy
                                    Rules</h3>
                                <div class="mt-2 flex flex-start mb-2">
                                    <x-button type="button" wire:click="addRule"
                                        class="bg-green-700 text-white px-3 py-1 rounded-md hover:bg-green-800 transition-all duration-200 text-xs uppercase">
                                        Add Rule
                                    </x-button>
                                </div>

                                <div class="flex flex-col sm:flex-row flex-wrap gap-3">
                                    @foreach ($originalCombinations as $index => $rule)
                                        <div class="bg-white border border-gray-200 rounded-md shadow-sm p-3">
                                            <div class="grid grid-cols-2 gap-2 sm:gap-4">
                                                <div>
                                                    <label for="adults_{{ $index }}"
                                                        class="block text-xs font-medium text-gray-700 mb-1">Adults</label>
                                                    <input type="number" wire:model="originalCombinations.{{ $index }}.adults"
                                                        id="adults_{{ $index }}" min="0" onwheel="this.blur()"
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-md focus:ring-primary-500 focus:border-primary-500 block w-full p-2">
                                                    @error("originalCombinations.{$index}.adults")
                                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                                    @enderror
                                                </div>


                                                <div>
                                                    <label for="kids_{{ $index }}"
                                                        class="block text-xs font-medium text-gray-700 mb-1">Kids</label>
                                                    <input type="number" wire:model="originalCombinations.{{ $index }}.kids"
                                                        id="kids_{{ $index }}" min="0" onwheel="this.blur()"
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-md focus:ring-primary-500 focus:border-primary-500 block w-full p-2">
                                                    @error("originalCombinations.{$index}.kids")
                                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                            </div>

                                            <div class="mt-2 flex justify-end">
                                                <button type="button" wire:click="removeRule({{ $index }})"
                                                    class="bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600 transition-all duration-200 text-xs">
                                                    Remove
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                            </div>
                        @endif

                        <!-- Max Guests for 'whole_number' -->
                        @if ($occupancy_type === 'whole_number')
                            <div>
                                <label for="max_guests"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Maximum
                                    Guests
                                    <span class="text-red-500">*</span></label>
                                <input type="number" wire:model="max_guests" id="max_guests" required
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                                                                         dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400" placeholder="Ex. 2"
                                    onwheel="this.blur()" />
                                @error('max_guests')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif
                    </div>

                    <!-- Turnover Duration -->
                    <div>
                        <label for="turnover_duration"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Turnover
                            Duration (Hours) <span class="text-red-500">*</span></label>
                        <input type="number" wire:model="turnover_duration" id="turnover_duration" min="1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                            dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                            placeholder="Ex. 3 hours" onwheel="this.blur()" />
                        @error('turnover_duration')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Room Status -->
                    <div>
                        <label for="property_status"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Room
                            Status <span class="text-red-500">*</span></label>
                        <select wire:model="property_status" id="property_status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                            dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                            <option value="available">Available</option>
                            <option value="out_of_service">Out of Service</option>
                        </select>
                        @error('property_status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Base Rate -->
                    <div>
                        <label for="amount" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Base
                            Rate <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="amount" id="amount" required class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-green-600 focus:border-green-600
                            dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                            placeholder="Ex. 2,800.00" onwheel="this.blur()" />
                        @error('amount')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Extra Person Charge -->
                    <div>
                        <label for="extra_person_charge"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Extra
                            Person Charge <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="extra_person_charge" id="extra_person_charge" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-green-600 focus:border-green-600
                            dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                            placeholder="Ex. 1,000.00" onwheel="this.blur()" />
                        @error('extra_person_charge')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Free Breakfast Inclusion -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Free Breakfast Inclusion
                        </label>
                        <div class="flex items-center gap-3">
                            <span class="text-gray-800 dark:text-gray-200 text-sm">Not Included</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model="freebies" id="freebies" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-green-500 rounded-full peer peer-checked:bg-green-600 transition
                                    ">
                                </div>
                                <div
                                    class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition peer-checked:translate-x-5">
                                </div>
                            </label>
                            <span class="text-gray-800 dark:text-gray-200 text-sm">Included</span>
                        </div>
                    </div>

                    <!-- Available Amenities (Dynamic) -->
                    <div class="sm:col-span-2">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Amenities</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                            @foreach ($amenities as $amenitiy)
                                <div class="flex items-center">
                                    <input type="checkbox" wire:model="selectedFeatures" value="{{ $amenitiy->id }}"
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded-sm focus:ring-blue-500">
                                    <label class="ms-2 text-sm font-medium text-gray-900">
                                        {{ $amenitiy->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('selectedFeatures')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>


                    {{-- <div class=" col-span-2">
                        <h3 class="block mb-2 text-sm font-semibold text-gray-900">Occupancy Rules</h3>
                        <div class="mt-2 flex flex-start mb-2">
                            <button type="button" wire:click="addRule"
                                class="bg-green-600 text-white px-3 py-1 rounded-md hover:bg-green-700 transition-all duration-200 text-sm">
                                Add Rule
                            </button>
                        </div>
                        <div class="flex flex-col sm:flex-row flex-wrap gap-3">
                            @foreach ($occupancy_rules as $index => $rule)
                            <div class="bg-white border border-gray-200 rounded-md shadow-sm p-3">
                                <div class="grid grid-cols-2 gap-2 sm:gap-4">
                                    <div>
                                        <label for="adults_{{ $index }}"
                                            class="block text-xs font-medium text-gray-700 mb-1">Adults</label>
                                        <input type="number" wire:model="occupancy_rules.{{ $index }}.adults"
                                            id="adults_{{ $index }}" min="0"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-md focus:ring-primary-500 focus:border-primary-500 block w-full p-2">
                                        @error("occupancy_rules.{$index}.adults")
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="kids_{{ $index }}"
                                            class="block text-xs font-medium text-gray-700 mb-1">Kids</label>
                                        <input type="number" wire:model="occupancy_rules.{{ $index }}.kids"
                                            id="kids_{{ $index }}" min="0"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-md focus:ring-primary-500 focus:border-primary-500 block w-full p-2">
                                        @error("occupancy_rules.{$index}.kids")
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mt-2 flex justify-end">
                                    <button type="button" wire:click="removeRule({{ $index }})"
                                        class="bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600 transition-all duration-200 text-xs">
                                        Remove
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div> --}}

                    <!-- Image Upload -->
                    <div class="mb-4 col-span-2">
                        <!-- Section Title -->
                        <label for="newImageInput"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Room
                            Image(s)</label>

                        <div class="flex flex-wrap gap-4" wire:sortable="reorderImages">
                            @if ($displayImages && count($displayImages) > 0)
                                @foreach ($displayImages as $image)
                                    <div class="relative shrink-0" wire:sortable.item="{{ $image['id'] }}"
                                        wire:key="image-{{ $image['id'] }}">

                                        <!-- Image Preview -->
                                        @if (isset($image['object']) && method_exists($image['object'], 'temporaryUrl'))
                                            <img src="{{ $image['object']->temporaryUrl() }}"
                                                class="w-52 h-40 object-cover rounded-md shadow-sm" alt="Image Preview">
                                        @elseif (isset($image['path']))
                                            <img src="{{ asset('storage/' . $image['path']) }}"
                                                class="w-52 h-40 object-cover rounded-md shadow-sm" alt="Stored Image">
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
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
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
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
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
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                                </path>
                            </svg>
                            <span class="dark:text-gray-200">Uploading...</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between items-center space-y-2 mt-6">
                    <x-ghost-button onclick="history.back()" type="button">
                        Cancel
                    </x-ghost-button>
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
                {{ __('Are you sure you want to save changes to this item?') }}
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('confirmEditItem', false)" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-button class="ms-3 bg-green text-white" wire:click="updateRoom({{ $room->id }})"
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