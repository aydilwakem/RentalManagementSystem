<div>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white mb-1">
            {{ __('Create Maintenance Report') }}
        </h2>
        <!-- Navigation -->
        <x-breadcrumbs :items="[
            ['label' => 'Maintenance Requests', 'url' => route('admin.maintenances')],
            ['label' => 'Create Maintenance Request', 'url' => route('admin.create-maintenance')],
        ]" />
    </x-slot>

    <!-- Body Container -->
    <div>
        <div
            class="mx-auto max-w-full sm:px-6 lg:px-8 bg-white rounded-xl border shadow-md p-6 dark:bg-gray-700 dark:border-gray-600 dark:text-white">

            <div class="relative flex items-center mb-4">
                <!-- Title -->
                <h2 class="text-2xl font-bold text-gray-900 w-full text-center dark:text-white">Add New Maintenance</h2>

                <!-- Back Button -->
                <button onclick="window.location.href='{{ route('admin.maintenances') }}'" wire:navigate
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute right-0 translate-y-[-12px]">
                    <span class="leading-none translate-y-[-3px]">&times;</span>
                </button>
            </div>

            <!-- Form container -->
            <form wire:submit.prevent="">
                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                    <!-- Maintenance Name -->
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Name <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="name" id="name" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                            placeholder="Ex. Leaky Faucet">
                        @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Property ID -->
                    <div>
                        <label for="property_id"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Assigned
                            Property <span class="text-red-500">*</span></label>
                        <select wire:model="property_id" id="property_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                            <option value="">Select Property</option>
                            @foreach ($properties as $property)
                            <option value="{{ $property->id }}">{{ $property->name_number }}</option>
                            @endforeach
                        </select>
                        @error('property_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Maintenance Description -->
                    <div class="sm:col-span-2">
                        <label for="description"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Description <span class="text-red-500">*</span></label>
                        <textarea wire:model="description" id="description" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 resize-none
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                            placeholder="Ex. Repair or replacement of components to stop water leakage from a faucet, preventing water waste and potential damage."></textarea>
                        @error('description')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>


                    <!-- Reported At -->
                    <div>
                        <label for="reported_at"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Date Reported
                            <span class="text-red-500">*</span></label>
                        <input type="date" wire:model="reported_at" id="reported_at" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                        @error('reported_at')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Resolved At -->
                    <div>
                        <label for="resolved_at"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Date Resolved
                        </label>
                        <input type="date" wire:model="resolved_at" id="resolved_at" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                        @error('resolved_at')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div x-data="{ showPlanned: @entangle('priority_status').defer }" class="sm:col-span-2">
                        <!-- Priority Status -->
                        <div class="mb-4">
                            <label for="priority_status"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Priority
                                Status <span class="text-red-500">*</span></label>
                            <select wire:model="priority_status" x-model="showPlanned" id="priority_status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                                dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                                <option value="">Select Priority Status</option>
                                <option value="emergency">Emergency</option>
                                <option value="urgent">Urgent</option>
                                <option value="routine">Routine</option>
                                <option value="planned">Planned</option>
                            </select>
                            @error('priority_status')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Planned Date & Time Field -->
                        <div x-show="showPlanned === 'planned'" class="sm:col-span-2" x-cloak class="sm:col-span-2">
                            <label for="planned_datetime"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Planned
                                Date
                                & Time</label>
                            <input type="datetime-local" wire:model="planned_datetime" id="planned_datetime" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                                dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                            @error('planned_datetime')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Routine Date & Time Field -->
                        <div x-show="showPlanned === 'routine'" class="sm:col-span-2" x-cloak class="sm:col-span-2">
                            <label for="routine_datetime"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Routine
                                Date
                                & Time</label>
                            <input type="datetime-local" wire:model="routine_datetime" id="routine_datetime" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                                dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                            @error('routine_datetime')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Image Upload --}}
                    <div class="mb-4 col-span-2">
                        <label for="newImageInput"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Maintenance
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
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
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
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z"></path>
                            </svg>
                            <span>Uploading...</span>
                        </div>
                    </div>


                    {{-- Resolved Images Upload --}}
                    <div class="mb-4 col-span-2">
                        <label for="resolvedImageInput"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Resolved
                            Image(s)</label>
                        <div class="flex flex-wrap gap-4" wire:sortable="reorderImages">
                            {{-- Combine both arrays for display and sorting --}}
                            @php
                            $displayImages = array_merge($resolvedImagePreviews, $resolvedPersistedPaths);
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
                                <button type="button" wire:click="removeResolvedImage({{ $index }})"
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
                            <label for="resolvedImageInput" class="cursor-pointer shrink-0" wire:loading.remove
                                wire:target="resolvedImages">
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
                            <label for="resolvedImageInput" class="cursor-pointer shrink-0" wire:loading.remove
                                wire:target="resolvedImages">
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

                            <!-- Hidden file input -->
                            <input multiple type="file" wire:model="resolvedImages" id="resolvedImageInput"
                                accept="image/png, image/jpeg" class="hidden">

                            @error('resolvedImages.*')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Spinner loading indicator -->
                        <div wire:loading wire:target="resolvedImages" class="flex items-center justify-start mt-2">
                            <svg class="animate-spin h-5 w-5 mr-2 text-green-700" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
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
                        Create Maintenance
                    </x-button>
                </div>
            </form>
            <!-- Create Confirmation Modal -->
            <x-dialog-modal wire:model.live="confirmCreateItem">
                <x-slot name="title">
                    {{ __('Create Maintenance') }}
                </x-slot>

                <x-slot name="content">
                    {{ __('Are you sure you want to add this item?') }}
                </x-slot>

                <x-slot name="footer">
                    <x-secondary-button wire:click="$set('confirmCreateItem', false)" wire:loading.attr="disabled">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-button class="ms-3 bg-green text-white" wire:click="saveMaintenance"
                        wire:loading.attr="disabled">
                        {{ __('Create Maintenance') }}
                    </x-button>
                </x-slot>
            </x-dialog-modal>
        </div>
    </div>
</div>