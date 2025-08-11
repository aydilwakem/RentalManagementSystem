<div>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white mb-1">
            {{ __('Create Activity') }}
        </h2>
        <!-- Navigation -->
        <x-breadcrumbs :items="[
        ['label' => 'Activities', 'url' => route('admin.activities')],
        ['label' => 'Create Activity', 'url' => route('admin.create-activity')],
    ]" />
    </x-slot>

    <!-- Body Container -->
    <div class="py-3">
        <div
            class="mx-auto max-w-5xl sm:px-6 lg:px-8 bg-white rounded-xl border shadow-md p-6 dark:bg-gray-700 dark:border-gray-600 dark:text-white">

            <div class="relative flex items-center mb-4">
                <!-- Title -->
                <h2 class="text-2xl font-bold text-gray-900 w-full text-center dark:text-white">Add New Activity</h2>

                <!-- Back Button -->
                <button onclick="window.location.href='{{ route('admin.activities') }}'" wire:navigate
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute right-0 translate-y-[-12px]">
                    <span class="leading-none translate-y-[-3px]">&times;</span>
                </button>
            </div>

            <!-- Form container -->
            <form wire:submit.prevent="">
                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                    <!-- Name of Activity -->
                    <div>
                        <label for="name"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Activity Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" wire:model="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                            placeholder="Ex. Coffee Farm Tour" required>

                        @error('name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>


                    <!-- Amount -->
                    <div>
                        <label for="amount"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Amount <span
                                class="text-red-500">*</span></label>
                        <input type="number" wire:model="amount" id="amount" step="0.01" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                            placeholder="Ex. 1,000.00">
                        @error('amount')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Description</label>
                        <textarea wire:model="description" id="description" rows="4" class="block p-2.5 max-h-20 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-green-600 focus:border-green-600 resize-none
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                            placeholder="Ex. Discover the journey from bean to cup on our immersive coffee farm tour."></textarea>
                        @error('description')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Inclusions -->
                    <div>
                        <label for="inclusions"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Inclusions</label>
                        <textarea wire:model="inclusions" id="inclusions" rows="3" class="block p-2.5  max-h-20 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-green-600 focus:border-green-600 resize-none
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                            placeholder="Ex. Farm entrance fee, coffee tasting, light snacks, guide services."></textarea>
                        @error('inclusions')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Schedule Type -->
                    <div class="mt-4">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Schedule
                            Type</label>
                        <select wire:model.live="schedule_type"
                            class="block w-full text-sm rounded-lg border p-2 bg-gray-50 border-gray-300 focus:ring-green-600 focus:border-green-600 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                            <option value="no_schedule">No schedule</option>
                            <option value="system">System will define schedule</option>
                            <option value="guest">Guest will choose preferred time</option>
                        </select>
                        @error('schedule_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Available Times (if system selected) -->
                    @if ($schedule_type === 'system')
                        <div class="mt-4">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Available
                                Times</label>

                            @foreach ($available_times as $index => $time)
                                <div class="flex items-center gap-2 mb-2">
                                    <input type="time" wire:model="available_times.{{ $index }}"
                                        class="w-full text-sm rounded-lg border p-2 bg-gray-50 border-gray-300 focus:ring-green-600 focus:border-green-600 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                                    <button type="button" wire:click="removeTime({{ $index }})"
                                        class="text-red-600 hover:underline">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            @endforeach

                            <button type="button" wire:click="addTime" class="mt-2 text-green-600 hover:underline text-sm">
                                <i class="fas fa-plus-circle mr-1"></i>Add Time
                            </button>

                            @error('available_times.*') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    @endif


                    <!-- Image Upload -->
                    <div class="mb-4 col-span-2">
                        <label for="newImageInput"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Activity
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

                </div>

                <div class="flex justify-between items-center space-y-2 mt-6">
                    <x-ghost-button onclick="history.back()" type="button">
                        Cancel
                    </x-ghost-button>
                    <x-button type="submit" wire:loading.attr="disabled" wire:target="image" wire:click="confirmCreate">
                        Create Activity
                    </x-button>
                </div>

            </form>

            <!-- Create Confirmation Modal -->
            <x-dialog-modal wire:model.live="confirmCreateItem">
                <x-slot name="title">
                    {{ __('Create Activity') }}
                </x-slot>

                <x-slot name="content">
                    {{ __('Are you sure you want to add this item?') }}
                </x-slot>

                <x-slot name="footer">
                    <x-secondary-button wire:click="$set('confirmCreateItem', false)" wire:loading.attr="disabled">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-button class="ms-3 bg-green text-white" wire:click="saveActivity" wire:loading.attr="disabled">
                        {{ __('Create Activity') }}
                    </x-button>
                </x-slot>
            </x-dialog-modal>
        </div>
    </div>
</div>