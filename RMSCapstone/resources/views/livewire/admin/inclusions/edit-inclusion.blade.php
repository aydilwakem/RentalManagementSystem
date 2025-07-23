<div>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-1 dark:text-white">
            {{ __('Edit Inclusion') }}
        </h2>
                <!-- Navigation -->
        <x-breadcrumbs :items="[
            ['label' => 'Inclusions', 'url' => route('admin.inclusions')],
            ['label' => 'View Inclusion', 'url' => route('admin.view-inclusion', ['inclusion' => $inclusion->id])],
            ['label' => 'Edit Inclusion', 'url' => route('admin.edit-inclusion', ['inclusion' => $inclusion->id])],
        ]" />
    </x-slot>

    <!-- Body Container -->
    <div class="py-3">
        <div class="mx-auto max-w-2xl sm:px-6 lg:px-8 bg-white rounded-xl border shadow-md p-6 dark:bg-gray-700 dark:border-gray-600">

            <div class="relative flex items-center mb-4">
                <!-- Title -->
                <h2 class="text-2xl font-bold text-gray-900 w-full text-center dark:text-white">Edit Inclusion</h2>

                <!-- Back Button -->
                <button onclick="history.back()"
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute right-0 translate-y-[-12px]">
                    <span class="leading-none translate-y-[-3px]">&times;</span>
                </button>
            </div>

            <!-- Form Container -->
            <form wire:submit.prevent="" class="flex flex-col space-y-6 min-h-[200px]">

                <!-- Name of Inclusion -->
                <div class="mt-4">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Inclusion
                        Name <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="name" id="name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                        dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                        placeholder="Type inclusion name" required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Space to push buttons to bottom -->
                <div class="flex-grow"></div>

                {{-- Action buttons --}}
                <div class="flex justify-between space-x-3 pt-4 mt-auto">
                    <x-ghost-button onclick="history.back()" type="button">
                        Cancel
                    </x-ghost-button>
                    <x-button type="submit" wire:click="confirmEdit({{ $inclusion->id }})">
                        Save Changes
                    </x-button>
                </div>
            </form>
        </div>

        <!-- Edit Confirmation Modal -->
        <x-dialog-modal wire:model.live="confirmEditItem">
            <x-slot name="title">
                {{ __('Edit Inclusion') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to save changes to this item?') }}
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('confirmEditItem', false)" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-button class="ms-3 bg-green text-white" wire:click="updateInclusion({{ $inclusion->id }})"
                    wire:loading.attr="disabled">
                    {{ __('Dave Changes') }}
                </x-button>
            </x-slot>
        </x-dialog-modal>
    </div>
