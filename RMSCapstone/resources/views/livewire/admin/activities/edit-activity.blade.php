<div class="min-h-[550px] container mx-auto p-6 bg-white rounded-lg">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Activity') }}
        </h2>
    </x-slot>
    <div class="shadow-lg rounded-lg p-6 max-w-2xl mx-auto border mt-4 mb-4 bg-white">
        <h2 class="mb-4 text-xl font-bold text-gray-900 text-center">Edit Activity</h2>

        <form wire:submit.prevent="">
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                <!-- Name of Activity -->
                <div class="sm:col-span-2">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Activity Name</label>
                    <input type="text" wire:model="name" id="name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                        placeholder="Type event category name" required>

                    @error('name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Description -->
                <div class="sm:col-span-2">
                    <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Description</label>
                    <textarea wire:model="description" id="description" rows="4"
                        class="block p-2.5 max-h-30 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 resize-none"
                        placeholder="Your event category description here"></textarea>
                    @error('description')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Amount -->
                <div class="sm:col-span-2">
                    <label for="amount" class="block mb-2 text-sm font-medium text-gray-900">Amount</label>
                    <input type="number" wire:model="amount" id="amount" step="0.01"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                        placeholder="Enter amount" required>
                    @error('amount')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Inclusions -->
                <div class="sm:col-span-2">
                    <label for="inclusions" class="block mb-2 text-sm font-medium text-gray-900">Inclusions</label>
                    <textarea wire:model="inclusions" id="inclusions" rows="3"
                        class="block p-2.5  max-h-20 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 resize-none"
                        placeholder="List inclusions here"></textarea>
                    @error('inclusions')
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
                        @elseif ($activity->image)
                        <!-- Show existing image from storage -->
                        <img src="{{ asset('storage/' . $activity->image) }}"
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
                    wire:click="confirmEdit({{ $activity->id }})">
                    Save Changes
                </x-button>
            </div>
        </form>
    </div>

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
</div>