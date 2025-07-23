<div>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-1 dark:text-white">
            {{ __('View Inclusion') }}
        </h2>
        <!-- Navigation -->
        <x-breadcrumbs :items="[
            ['label' => 'Inclusions', 'url' => route('admin.inclusions')],
            ['label' => 'View Inclusion', 'url' => route('admin.view-inclusion', ['inclusion' => $inclusion->id])],
        ]" />
    </x-slot>

    <!-- Body Container -->
    <div class="py-3">
        <div class="mx-auto max-w-2xl sm:px-6 lg:px-8 bg-white rounded-xl border shadow-md p-6 dark:bg-gray-700 dark:border-gray-600">

            <div class="relative flex items-center mb-4">
                <!-- Back Button -->
                <button onclick="window.location.href='{{ route('admin.inclusions') }}'"
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute top-0 right-0">
                    <span class="leading-none translate-y-[-3px]">&times;</span>
                </button>
            </div>


            <!-- Name -->
            <h2 class="mb-4 text-xl font-semibold leading-none text-gray-900 md:text-2xl text-center p-8 dark:text-white">
                Inclusion: {{ $inclusion->name }}
            </h2>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between space-x-4 mt-3 mb-3">

                <!-- Edit -->
                <x-ghost-button type="button" icon="fas fa-pen-to-square"
                    wire:navigate href="{{ route('admin.edit-inclusion', ['inclusion' => $inclusion->id]) }}">
                    Edit
                </x-ghost-button>

                <!-- Delete -->
                <x-danger-button type="button" icon="fas fa-trash"
                    wire:click="confirmDelete({{ $inclusion->id }})">
                    Delete
                </x-danger-button>

            </div>

            <!-- Delete Confirmation Modal -->
            <x-dialog-modal wire:model.live="confirmItemDelete" type="danger">
                <x-slot name="title">
                    {{ __('Delete Inclusion') }}
                </x-slot>

                <x-slot name="content">
                    {{ __('Are you sure you want to delete this item?') }}
                </x-slot>

                <x-slot name="footer">
                    <x-secondary-button wire:click="$set('confirmItemDelete', false)" wire:loading.attr="disabled">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-danger-button class="ms-3" wire:click="deleteInclusion({{ $inclusion->id }})"
                        wire:loading.attr="disabled">
                        {{ __('Delete Inclusion') }}
                    </x-danger-button>
                </x-slot>
            </x-dialog-modal>

        </div>
    </div>
