<div>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white mb-1">
            {{ __('View Event Category') }}
        </h2>
        <!-- Navigation -->
        <x-breadcrumbs :items="[
            ['label' => 'Event Categories', 'url' => route('admin.event-categories')],
            ['label' => 'View Event Category', 'url' => route('admin.view-event-category', ['eventCategory' => $eventCategory->id])],
        ]" />
    </x-slot>

    <!-- Body Container -->
    <div class="py-3">
        <div class="mx-auto max-w-2xl sm:px-6 lg:px-8 bg-white rounded-xl border shadow-md p-6 dark:bg-gray-700 dark:border-gray-600 dark:text-white">

            <div class="relative flex items-center mb-4">
                <!-- Title -->
                <h2 class="text-2xl font-bold text-gray-900 w-full text-center dark:text-white"> {{ $eventCategory->name }}</h2>

                <!-- Back Button -->
                <button onclick="window.location.href='{{ route('admin.event-categories') }}'"
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute right-0 translate-y-[-12px]">
                    <span class="leading-none translate-y-[-3px]">&times;</span>
                </button>
            </div>

            <!-- Description -->
            <div class="mb-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-2 mt-5 dark:text-gray-200">Description</h3>

                @if (!empty($eventCategory->description))
                    <div
                        class="bg-gray-50 p-4 rounded-lg border border-gray-200 text-gray-700 leading-relaxed shadow-sm text-justify
                        dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                        {{ $eventCategory->description }}
                    </div>
                @else
                    <p class="text-gray-500 italic dark:text-gray-200">No description provided.</p>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between space-x-4 mt-8 mb-3">

                <!-- Edit -->
                <x-ghost-button type="button" icon="fas fa-pen-to-square" wire:navigate
                    href="{{ route('admin.edit-event-category', ['eventCategory' => $eventCategory->id]) }}">
                    Edit
                </x-ghost-button>

                <!-- Delete -->
                <x-danger-button type="button" icon="fas fa-trash"
                    wire:click="confirmDelete({{ $eventCategory->id }})">
                    Delete
                </x-danger-button>
            </div>
            <!-- Delete Confirmation Modal -->
            <x-dialog-modal wire:model.live="confirmItemDelete" type="danger">
                <x-slot name="title">
                    {{ __('Delete Event Category') }}
                </x-slot>

                <x-slot name="content">
                    {{ __('Are you sure you want to delete this item?') }}
                </x-slot>

                <x-slot name="footer">
                    <x-secondary-button wire:click="$set('confirmItemDelete', false)" wire:loading.attr="disabled">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-danger-button class="ms-3" wire:click="deleteEventCategory" wire:loading.attr="disabled">
                        {{ __('Delete Event Category') }}
                    </x-danger-button>
                </x-slot>
            </x-dialog-modal>

            {{-- Cannot Delete Modal --}}
            <x-dialog-modal wire:model="cannotDeleteItem" type="ghost">
                <x-slot name="title">
                    {{ __('Unable to Delete') }}
                </x-slot>

                <x-slot name="content">
                    {{ __('This category is currently in use and cannot be deleted.') }}
                </x-slot>

                <x-slot name="footer">
                    <x-secondary-button wire:click="$set('cannotDeleteItem', false)" wire:loading.attr="disabled">
                        {{ __('OK') }}
                    </x-secondary-button>
                </x-slot>
            </x-dialog-modal>
        </div>
    </div>
</div>
