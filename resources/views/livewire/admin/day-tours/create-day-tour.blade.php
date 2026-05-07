<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white mb-1">
            {{ __('Create Day Tour Package') }}
        </h2>
        <x-breadcrumbs :items="[
            ['label' => 'Day Tours', 'url' => route('admin.day-tours')],
            ['label' => 'Create Day Tour', 'url' => route('admin.create-day-tour')],
        ]" />
    </x-slot>

    <div class="py-3">
        <div
            class="mx-auto max-w-7xl sm:px-6 lg:px-8 bg-white rounded-xl border shadow-md p-6 dark:bg-gray-700 dark:text-white dark:border-gray-600">

            <div class="relative flex items-center mb-4">
                <h2 class="text-2xl font-bold text-gray-900 w-full text-center dark:text-white">Add New Day Tour</h2>
                <button onclick="window.location.href='{{ route('admin.day-tours') }}'" wire:navigate
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute right-0 translate-y-[-12px]">
                    <span class="leading-none translate-y-[-3px]">&times;</span>
                </button>
            </div>

            <form wire:submit.prevent="">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                    <!-- Tour Name -->
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Tour Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="name" id="name" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 capitalize dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                            placeholder="Ex. Standard Day Tour Package">
                        @error('name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Base Price -->
                    <div>
                        <label for="base_price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Base Price <span class="text-red-500">*</span>
                        </label>
                        <input type="number" wire:model="base_price" id="base_price" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                            placeholder="Ex. ₱650.00" onwheel="this.blur()">
                        @error('base_price')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="col-span-1 md:col-span-2">
                        <label for="description"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Description
                        </label>
                        <textarea wire:model="description" id="description" rows="3"
                            class="resize-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                            placeholder="Describe the day tour package..."></textarea>
                        @error('description')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Duration -->
                    <div>
                        <label for="duration_hours"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Duration (Hours) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" wire:model="duration_hours" id="duration_hours" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                            placeholder="8" min="1" max="24">
                        @error('duration_hours')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Package Type -->
                    <div>
                        <label for="package_type"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Package Type <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="package_type" id="package_type" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                            <option value="without_room">Without Room</option>
                            <option value="with_room">With Room</option>
                        </select>
                        @error('package_type')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Start Time -->
                    <div>
                        <label for="start_time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Start Time <span class="text-red-500">*</span>
                        </label>
                        <input type="time" wire:model="start_time" id="start_time" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                        @error('start_time')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- End Time -->
                    <div>
                        <label for="end_time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            End Time <span class="text-red-500">*</span>
                        </label>
                        <input type="time" wire:model="end_time" id="end_time" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                        @error('end_time')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Inclusions -->
                    <div class="col-span-1 md:col-span-2">
                        <label for="inclusions" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Inclusions
                        </label>
                        <textarea wire:model="inclusions" id="inclusions" rows="4"
                            class="resize-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                            placeholder="List what's included in the tour..."></textarea>
                        @error('inclusions')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Terms & Conditions -->
                    <div class="col-span-1 md:col-span-2">
                        <label for="terms_conditions"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Terms & Conditions
                        </label>
                        <textarea wire:model="terms_conditions" id="terms_conditions" rows="4"
                            class="resize-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                            placeholder="Enter terms and conditions..."></textarea>
                        @error('terms_conditions')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Day Tour Images -->
                    <div class="col-span-1 md:col-span-2">
                        <label for="newImageInput"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Day Tour Images
                        </label>
                        <div class="flex flex-col md:flex-row flex-wrap gap-4" wire:sortable="reorderImages">
                            @php
                                $displayImages = array_merge($uploadedImagePreviews, $persistedImagePaths);
                            @endphp

                            @if ($displayImages && count($displayImages) > 0)
                                @foreach ($displayImages as $index => $image)
                                    <div class="relative shrink-0" wire:sortable.item="{{ $index }}"
                                        wire:key="image-{{ $index }}">
                                        @if (is_object($image) && method_exists($image, 'temporaryUrl'))
                                            <img src="{{ $image->temporaryUrl() }}"
                                                class="w-52 h-40 object-cover rounded-md shadow-sm">
                                        @else
                                            <img src="{{ asset('storage/' . $image) }}"
                                                class="w-52 h-40 object-cover rounded-md shadow-sm">
                                        @endif
                                        <button type="button" wire:click="removeImage({{ $index }})"
                                            class="absolute top-2 right-2 bg-gray-200 text-gray-500 rounded-full w-5 h-5 flex items-center justify-center hover:bg-red-300 hover:text-red-700 transition">
                                            ×
                                        </button>
                                        @if ($loop->first)
                                            <span
                                                class="absolute bottom-0 left-0 bg-black bg-opacity-50 text-white text-xs rounded-sm px-1">Main
                                                Image</span>
                                        @endif
                                    </div>
                                @endforeach

                                <label for="newImageInput" class="cursor-pointer shrink-0" wire:loading.remove
                                    wire:target="newImages">
                                    <div
                                        class="w-52 h-40 border-2 border-dashed border-gray-400 rounded-md flex items-center justify-center text-gray-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </div>
                                </label>
                            @else
                                <label for="newImageInput" class="cursor-pointer shrink-0" wire:loading.remove
                                    wire:target="newImages">
                                    <div
                                        class="w-52 h-40 border-2 border-dashed border-gray-400 rounded-md flex flex-col items-center justify-center text-gray-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        <span class="text-xs">Add image</span>
                                    </div>
                                </label>
                            @endif

                            <input multiple type="file" wire:model="newImages" id="newImageInput"
                                accept="image/png, image/jpeg" class="hidden">
                            @error('newImages.*')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Status
                        </label>
                        <div class="flex items-center gap-3">
                            <span class="text-gray-800 dark:text-gray-200 text-sm">Inactive</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model="is_active" value="1" class="sr-only peer">
                                <div
                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-green-500 rounded-full peer peer-checked:bg-green-600 transition">
                                </div>
                                <div
                                    class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition peer-checked:translate-x-5">
                                </div>
                            </label>
                            <span class="text-gray-800 dark:text-gray-200 text-sm">Active</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between items-center space-y-2 mt-6 gap-2">
                    <x-ghost-button onclick="window.location.href='{{ route('admin.day-tours') }}'" type="button">
                        Cancel
                    </x-ghost-button>

                    <x-button wire:loading.attr="disabled" wire:target="main_image,newImages"
                        wire:click="confirmCreate">
                        Create Day Tour
                    </x-button>
                </div>
            </form>

        </div>

        <!-- Create Confirmation Modal -->
        <x-dialog-modal wire:model.live="confirmCreateItem">
            <x-slot name="title">
                {{ __('Create Day Tour') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to create this day tour?') }}
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('confirmCreateItem', false)" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-button class="ms-3 bg-green text-white" wire:click="saveDayTour" wire:loading.attr="disabled">
                    {{ __('Create Day Tour') }}
                </x-button>
            </x-slot>
        </x-dialog-modal>
    </div>
</div>
