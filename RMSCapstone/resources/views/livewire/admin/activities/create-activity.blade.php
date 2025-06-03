<div>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Activity') }}
        </h2>
    </x-slot>

    <!-- Body Container -->
    <div class="py-3">
        <div class="mx-auto max-w-full sm:px-6 lg:px-8 bg-white rounded-xl border shadow-md p-6">

            <div class="relative flex items-center mb-4">
                <!-- Title -->
                <h2 class="text-2xl font-bold text-gray-900 w-full text-center">Add New Activity</h2>

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
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Activity Name <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="name" id="name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5"
                            placeholder="Ex. Coffee Farm Tour" required>

                        @error('name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>


                    <!-- Amount -->
                    <div>
                        <label for="amount" class="block mb-2 text-sm font-medium text-gray-900">Amount <span class="text-red-500">*</span></label>
                        <input type="number" wire:model="amount" id="amount" step="0.01"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5"
                            placeholder="Ex. 1,000.00">
                        @error('amount')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description"
                            class="block mb-2 text-sm font-medium text-gray-900">Description</label>
                        <textarea wire:model="description" id="description" rows="4"
                            class="block p-2.5 max-h-20 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-green-600 focus:border-green-600 resize-none"
                            placeholder="Ex. Discover the journey from bean to cup on our immersive coffee farm tour."></textarea>
                        @error('description')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Inclusions -->
                    <div>
                        <label for="inclusions" class="block mb-2 text-sm font-medium text-gray-900">Inclusions</label>
                        <textarea wire:model="inclusions" id="inclusions" rows="3"
                            class="block p-2.5  max-h-20 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-green-600 focus:border-green-600 resize-none"
                            placeholder="Ex. Farm entrance fee, coffee tasting, light snacks, guide services."></textarea>
                        @error('inclusions')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Image Upload -->
                    <div class="space-y-4">
                        <div>
                            <label for="image" class="block mb-2 text-sm font-medium text-gray-900">Upload
                                Image</label>
                            <input accept="image/png, image/jpeg" type="file" wire:model="image" id="image"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5">

                            <!-- Error Message -->
                            @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            <!-- Loading Indicator (Shows when file is being uploaded) -->
                            <div wire:loading wire:target="image" class="mt-2 flex items-center">
                                <!-- Spinner -->
                                <svg class="animate-spin h-5 w-5 text-green-700 mr-2" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                                    </path>
                                </svg>
                                <span>Uploading...</span>
                            </div>
                        </div>

                        <div>
                            @if ($image && method_exists($image, 'temporaryUrl'))
                                <div class="mt-2 relative inline-block">
                                    <img src="{{ $image->temporaryUrl() }}"
                                        class="w-32 h-32 object-cover rounded-lg shadow" alt="Image preview">
                                    <button type="button" wire:click="removeImage"
                                        class="absolute top-1 right-1 bg-gray-200 text-gray-500 rounded-full w-5 h-5 flex items-center justify-center text-sm font-semibold leading-none hover:bg-red-300 hover:text-red-700 transition"
                                        aria-label="Remove image">
                                        ×
                                    </button>
                                </div>
                            @else
                                <div wire:loading.remove wire:target="image"
                                    class="w-full h-48 flex items-center justify-center border-2 border-dashed border-gray-300 rounded-lg text-gray-400">
                                    No image selected
                                </div>
                            @endif

                        </div>
                    </div>

                </div>

                <div class="flex justify-between items-center space-y-2 mt-6">
                    <x-ghost-button onclick="history.back()" type="button">
                        Cancel
                    </x-ghost-button>
                    <x-button type="submit" wire:loading.attr="disabled" wire:target="image"
                        wire:click="confirmCreate">
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
