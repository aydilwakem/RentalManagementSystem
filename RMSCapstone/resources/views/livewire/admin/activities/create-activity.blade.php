<div class="mx-4 sm:mx-auto bg-white dark:bg-[#2A2A2A] rounded-2xl p-8">

    <h2 class="mb-4 text-xl font-bold text-gray-900 text-center">Add a new Activity</h2>

    <form wire:submit.prevent="">
        <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
            <!-- Name of Activity -->
            <div>
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Activity Name</label>
                <input type="text" wire:model="name" id="name"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                    placeholder="Type activity name" required>

                @error('name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>


            <!-- Amount -->
            <div>
                <label for="amount" class="block mb-2 text-sm font-medium text-gray-900">Amount</label>
                <input type="number" wire:model="amount" id="amount" step="0.01"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                    placeholder="Enter amount">
                @error('amount')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Description</label>
                <textarea wire:model="description" id="description" rows="4"
                    class="block p-2.5 max-h-20 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 resize-none"
                    placeholder="Your activity description here"></textarea>
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
            <div class="mb-4 col-span-2">
                <label for="images" class="block mb-2 text-sm font-medium text-gray-900">Upload Activity Image(s)</label>
                <div class="flex flex-wrap gap-4">
                    @if ($images && count($images) > 0)
                        @foreach ($images as $index => $image)
                            <div class="relative shrink-0">
                                <img src="{{ $image->temporaryUrl() }}"
                                    class="w-52 h-40 object-cover rounded-md shadow-sm" alt="Image Preview">
                                <button type="button" wire:click="removeImage({{ $index }})"
                                    class="absolute top-2 right-2 bg-gray-200 text-gray-500 rounded-full w-5 h-5 flex items-center justify-center text-sm font-semibold leading-none hover:bg-red-300 hover:text-red-700 transition">
                                    ×
                                </button>
                                @if ($loop->first)
                                    <span
                                        class="absolute bottom-0 left-0 bg-black bg-opacity-50 text-white text-xs rounded-sm px-1">Main
                                        Image</span>
                                @endif
                            </div>
                        @endforeach
                        <label for="imageInput" class="cursor-pointer shrink-0" wire:loading.remove
                            wire:target="images">
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
                        <label for="imageInput" class="cursor-pointer shrink-0" wire:loading.remove
                            wire:target="images">
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

                    <input multiple type="file" wire:model="images" id="imageInput"
                        accept="image/png, image/jpeg" class="hidden" @if (!$images || count($images) < 5)  @endif>

                    @error('images.*')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div wire:loading wire:target="images" class="flex items-center justify-start mt-2">
                    <svg class="animate-spin h-5 w-5 mr-2 text-green-700" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z"></path>
                    </svg>
                    <span>Uploading...</span>
                </div>
            </div>
        </div>

        <div class="flex justify-between items-center space-y-2 mt-6">
            <x-button onclick="history.back()" type="button"
                class="!bg-gray-200 !text-black hover:!bg-gray-300 focus:!ring-2 focus:!ring-gray-400 focus:!outline-none">
                Cancel
            </x-button>
            <x-button type="submit" wire:loading.attr="disabled" wire:target="image" wire:click="confirmCreate">
                Add Activity
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
