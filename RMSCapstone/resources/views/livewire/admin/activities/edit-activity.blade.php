<div class="mx-4 sm:mx-auto bg-white dark:bg-[#2A2A2A] rounded-2xl p-8">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Activity') }}
        </h2>
    </x-slot>
    <h2 class="mb-4 text-xl font-bold text-gray-900 text-center">Edit Activity</h2>

    <form wire:submit.prevent="">
        <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

            <!-- Name of Activity -->
            <div>
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Activity Name</label>
                <input type="text" wire:model="name" id="name"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                    placeholder="Type event category name" required>

                @error('name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Amount -->
            <div>
                <label for="amount" class="block mb-2 text-sm font-medium text-gray-900">Amount</label>
                <input type="number" wire:model="amount" id="amount" step="0.01"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                    placeholder="Enter amount" required>
                @error('amount')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Description</label>
                <textarea wire:model="description" id="description" rows="4"
                    class="block p-2.5 max-h-20 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 resize-none"
                    placeholder="Your event category description here"></textarea>
                @error('description')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>


            <!-- Inclusions -->
            <div>
                <label for="inclusions" class="block mb-2 text-sm font-medium text-gray-900">Inclusions</label>
                <textarea wire:model="inclusions" id="inclusions" rows="3"
                    class="block p-2.5  max-h-20 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 resize-none"
                    placeholder="List inclusions here"></textarea>
                @error('inclusions')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Image Upload -->
            <div class="space-y-4">
                <div>
                    <label for="image" class="block mb-2 text-sm font-medium text-gray-900">Upload New Image
                        (Optional)</label>
                    <input type="file" wire:model="newImage" id="image" accept="image/png, image/jpeg"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">

                    @error('newImage')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <div wire:loading wire:target="newImage" class="mt-2 text-gray-600">Uploading image...</div>
                </div>

                <!-- Image Preview -->
                <div class="mt-2">
                    @if ($newImage)
                        <!-- Show new uploaded image -->
                        <img src="{{ $newImage->temporaryUrl() }}"
                            class="mt-4 w-full h-48 object-cover rounded-lg shadow">
                    @elseif ($activity->image)
                        <!-- Show existing image from storage -->
                        <div class="relative inline-block">
                            <img src="{{ asset('storage/' . $activity->image) }}"
                                class="mt-4 w-full h-48 object-cover rounded-lg shadow">
                            <button type="button" wire:click="confirmImageDelete"
                                class="absolute top-6 right-2 bg-gray-200 text-gray-500 rounded-full w-5 h-5 flex items-center justify-center text-sm font-semibold leading-none hover:bg-red-300 hover:text-red-700 transition"
                                aria-label="Remove image">
                                ×
                            </button>
                        </div>
                    @else
                        <!-- Show default image if no image exists -->
                        <img src="{{ asset('images/rms-default.png') }}"
                            class="mt-4 w-full h-48 object-cover rounded-lg shadow">
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
                wire:click="confirmEdit({{ $activity->id }})">
                Save Changes
            </x-button>
        </div>
    </form>


    <!-- Edit Confirmation Modal -->
    <x-dialog-modal wire:model.live="confirmEditItem">
        <x-slot name="title">
            {{ __('Edit Activity') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to save changes on this item?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('confirmEditItem', false)" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ms-3 bg-green text-white" wire:click="updateActivity({{ $activity->id }})"
                wire:loading.attr="disabled">
                {{ __('Edit Activity') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Remove Image Confirmation Modal -->
    <x-dialog-modal wire:model.live="confirmDeleteImage">
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
