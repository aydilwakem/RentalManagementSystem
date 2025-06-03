<div>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Room Category') }}
        </h2>
    </x-slot>

    <!-- Body Container -->
    <div class="py-3">
        <div class="mx-auto max-w-2xl sm:px-6 lg:px-8 bg-white rounded-xl border shadow-md p-6">

            <div class="relative flex items-center mb-4">
                <!-- Title -->
                <h2 class="text-2xl font-bold text-gray-900 w-full text-center">Add New Room Category</h2>

                <!-- Back Button -->
                <button onclick="window.location.href='{{ route('admin.room-categories') }}'" wire:navigate
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute right-0 translate-y-[-12px]">
                    <span class="leading-none translate-y-[-3px]">&times;</span>
                </button>
            </div>

            <!-- Form container -->
            <form wire:submit.prevent="">
                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                    <!-- Name of Category -->
                    <div class="sm:col-span-2">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Name <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="name" id="name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5"
                            placeholder="Ex. Cozy Rooms" required>
                        @error('name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="sm:col-span-2">
                        <label for="description"
                            class="block mb-2 text-sm font-medium text-gray-900">Description</label>
                        <textarea wire:model="description" id="description" rows="8"
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-green-600 focus:border-green-600 resize-none"
                            placeholder="Ex. Warm, comfy spaces ideal for solo stays or couples."></textarea>
                        @error('description')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
                <!-- Actions Buttons -->
                <div class="flex justify-between items-center space-y-2 mt-6">
                    <x-ghost-button onclick="history.back()" type="button">
                        Cancel
                    </x-ghost-button>
                    <x-button wire:loading.attr="disabled" wire:target="image" wire:click="confirmCreate">
                        Add Category
                    </x-button>
                </div>
            </form>
        </div>
    </div>
    <!-- Create Confirmation Modal -->
    <x-dialog-modal wire:model.live="confirmCreateItem">
        <x-slot name="title">
            {{ __('Create Room Category') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to create this item?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('confirmCreateItem', false)" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ms-3 bg-green text-white" wire:click="saveCategory" wire:loading.attr="disabled">
                {{ __('Create Room Category') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
