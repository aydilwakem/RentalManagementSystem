<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white mb-1">
            {{ __('Edit Day Tour') }}
        </h2>
        <x-breadcrumbs :items="[
            ['label' => 'Day Tours', 'url' => route('admin.day-tours')],
            ['label' => 'View Day Tour', 'url' => route('admin.view-day-tour', ['dayTour' => $dayTour->id])],
            ['label' => 'Edit Day Tour', 'url' => route('admin.edit-day-tour', ['dayTour' => $dayTour->id])],
        ]" />
    </x-slot>

    <div class="py-3">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 bg-white rounded-xl border shadow-md p-6 dark:bg-gray-700 dark:border-gray-600">

            <div class="relative flex items-center mb-4">
                <h2 class="text-2xl font-bold text-gray-900 w-full text-center dark:text-white">Edit Day Tour</h2>
                <button onclick="history.back()"
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute right-0 translate-y-[-12px]">
                    <span class="leading-none translate-y-[-3px]">&times;</span>
                </button>
            </div>

            <form wire:submit.prevent="">
                <div class="grid gap-4 md:grid-cols-2 sm:gap-6">
                    <!-- Tour Name -->
                    <div class="md:col-span-2">
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

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Description <span class="text-red-500">*</span>
                        </label>
                        <textarea wire:model="description" id="description" rows="4"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                            placeholder="Describe the day tour package..."></textarea>
                        @error('description')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Duration and Time -->
                    <div>
                        <label for="duration_hours" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Duration (Hours) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" wire:model="duration_hours" id="duration_hours" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                            placeholder="8" min="1" max="24">
                        @error('duration_hours')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="max_guests" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Maximum Guests <span class="text-red-500">*</span>
                        </label>
                        <input type="number" wire:model="max_guests" id="max_guests" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                            placeholder="50" min="1" max="1000">
                        @error('max_guests')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Start and End Time -->
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

                    <!-- Base Price -->
                    <div>
                        <label for="base_price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Base Price <span class="text-red-500">*</span>
                        </label>
                        <input type="number" wire:model="base_price" id="base_price" required step="0.01"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                            placeholder="0.00" min="0" onwheel="this.blur()">
                        @error('base_price')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Status
                        </label>
                        <div class="flex items-center gap-3">
                            <span class="text-gray-800 dark:text-gray-200 text-sm">Inactive</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model="is_active" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-green-500 rounded-full peer peer-checked:bg-green-600 transition"></div>
                                <div class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition peer-checked:translate-x-5"></div>
                            </label>
                            <span class="text-gray-800 dark:text-gray-200 text-sm">Active</span>
                        </div>
                    </div>

                    <!-- Inclusions -->
                    <div>
                        <label for="inclusions" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Inclusions
                        </label>
                        <textarea wire:model="inclusions" id="inclusions" rows="4"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                            placeholder="List what's included in the tour (one per line)&#10;• Swimming pool access&#10;• Lunch buffet&#10;• Welcome drinks"></textarea>
                        @error('inclusions')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Exclusions -->
                    <div>
                        <label for="exclusions" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Exclusions
                        </label>
                        <textarea wire:model="exclusions" id="exclusions" rows="4"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                            placeholder="List what's not included in the tour (one per line)&#10;• Alcoholic beverages&#10;• Spa services&#10;• Transportation"></textarea>
                        @error('exclusions')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Terms & Conditions -->
                    <div class="md:col-span-2">
                        <label for="terms_conditions" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Terms & Conditions
                        </label>
                        <textarea wire:model="terms_conditions" id="terms_conditions" rows="4"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                            placeholder="Enter terms and conditions..."></textarea>
                        @error('terms_conditions')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Main Image Upload -->
                    <div class="mb-4">
                        <label for="newMainImage" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Main Image
                        </label>

                        @if ($dayTour->main_image)
                            <div class="relative mb-2">
                                <img src="{{ asset('storage/' . $dayTour->main_image) }}" class="w-52 h-40 object-cover rounded-md shadow-sm" alt="Current Main Image">
                                <span class="absolute bottom-0 left-0 bg-black bg-opacity-50 text-white text-xs rounded-sm px-1">
                                    Current Main Image
                                </span>
                            </div>
                        @endif

                        @if ($newMainImage)
                            <div class="relative mb-2">
                                <img src="{{ $newMainImage->temporaryUrl() }}" class="w-52 h-40 object-cover rounded-md shadow-sm" alt="New Main Image Preview">
                                <button type="button" wire:click="$set('newMainImage', null)"
                                    class="absolute top-2 right-2 bg-gray-200 text-gray-500 rounded-full w-5 h-5 flex items-center justify-center text-sm font-semibold leading-none hover:bg-red-300 hover:text-red-700 transition">
                                    ×
                                </button>
                                <span class="absolute bottom-0 left-0 bg-black bg-opacity-50 text-white text-xs rounded-sm px-1">
                                    New Main Image
                                </span>
                            </div>
                        @endif

                        <input type="file" wire:model="newMainImage" id="newMainImage" accept="image/png, image/jpeg"
                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        @error('newMainImage')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Additional Images Upload -->
                    <div class="mb-4">
                        <label for="newImageInput" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Additional Images
                        </label>
                        <div class="flex flex-wrap gap-4">
                            @if (count($displayImages) > 0)
                                @foreach ($displayImages as $image)
                                    <div class="relative shrink-0">
                                        @if (isset($image['path']))
                                            <img src="{{ asset('storage/' . $image['path']) }}" class="w-52 h-40 object-cover rounded-md shadow-sm" alt="Stored Image">
                                        @elseif (isset($image['object']))
                                            <img src="{{ $image['object']->temporaryUrl() }}" class="w-52 h-40 object-cover rounded-md shadow-sm" alt="New Image Preview">
                                        @endif

                                        <button type="button" wire:click="confirmImageDelete('{{ $image['id'] }}')"
                                            class="absolute top-2 right-2 bg-gray-200 text-gray-500 rounded-full w-5 h-5 flex items-center justify-center text-sm font-semibold leading-none hover:bg-red-300 hover:text-red-700 transition">
                                            ×
                                        </button>

                                        @if ($loop->first && isset($image['path']) && $image['path'] === $dayTour->main_image)
                                            <span class="absolute bottom-0 left-0 bg-black bg-opacity-50 text-white text-xs rounded-sm px-1">
                                                Main Image
                                            </span>
                                        @endif
                                    </div>
                                @endforeach
                            @endif

                            <label for="newImageInput" class="cursor-pointer shrink-0" wire:loading.remove wire:target="newImages">
                                <div class="w-52 h-40 border-2 border-dashed border-gray-400 rounded-md flex flex-col items-center justify-center text-gray-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    <span class="text-xs">Add image</span>
                                </div>
                            </label>

                            <input multiple type="file" wire:model="newImages" id="newImageInput" accept="image/png, image/jpeg" class="hidden">
                        </div>

                        <div wire:loading wire:target="newImages" class="flex items-center justify-start mt-2">
                            <svg class="animate-spin h-5 w-5 mr-2 text-green-700 dark:text-green-300" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z"></path>
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
                    <x-button type="submit" wire:loading.attr="disabled" wire:target="newMainImage,newImages" wire:click="confirmEdit">
                        Save Changes
                    </x-button>
                </div>
            </form>
        </div>

        <!-- Edit Confirmation Modal -->
        <x-dialog-modal wire:model.live="confirmEditItem">
            <x-slot name="title">
                {{ __('Edit Day Tour') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to save changes to this day tour?') }}
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('confirmEditItem', false)" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-button class="ms-3 bg-green text-white" wire:click="updateDayTour" wire:loading.attr="disabled">
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
