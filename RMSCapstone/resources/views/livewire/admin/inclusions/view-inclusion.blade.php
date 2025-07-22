<div>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Inclusion') }}
        </h2>
    </x-slot>

    <!-- Body Container -->
    <div class="py-3">
        <div class="mx-auto max-w-2xl sm:px-6 lg:px-8 bg-white rounded-xl border shadow-md p-6">

            <div class="relative flex items-center mb-4">
                <!-- Back Button -->
                <button onclick="window.location.href='{{ route('admin.inclusions') }}'"
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute top-0 right-0">
                    <span class="leading-none translate-y-[-3px]">&times;</span>
                </button>
            </div>


            <!-- Name -->
            <h2 class="mb-4 text-xl font-semibold leading-none text-gray-900 md:text-2xl text-center p-8">
                Inclusion: {{ $inclusion->name }}
            </h2>

            <h3 class="text-lg font-bold text-green-800 mb-3 dark:text-green-300">Inclusion Details</h3>
            <div
                class="bg-gray-50 rounded-lg p-6 mb-6 text-gray-600 dark:bg-gray-600 dark:text-gray-200 border dark:border-gray-500">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2 text-gray-600 dark:text-gray-200">
                    <div><strong>Quantity:</strong> {{ $inclusion->quantity }} </div>
                    <div><strong>Inclusion Type:</strong> {{ ucfirst($inclusion->property_feature_type) }} </div>
                    <div><strong>Status:</strong>
                        @if ($inclusion->is_active)
                        <span
                            class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-green-100 text-green-500">
                            Active
                        </span>
                        @else
                        <span class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-red-100 text-red-500">
                            Inactive
                        </span>
                        @endif
                    </div>
                </div>

            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between space-x-4 mt-3 mb-3">

                <!-- Edit -->
                <x-ghost-button type="button" icon="fas fa-pen-to-square" wire:navigate
                    href="{{ route('admin.edit-inclusion', ['inclusion' => $inclusion->id]) }}">
                    Edit
                </x-ghost-button>

                <!-- Delete -->
                <x-danger-button type="button" icon="fas fa-trash" wire:click="confirmDelete({{ $inclusion->id }})">
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

            {{-- Cannot Delete Modal --}}
            <x-dialog-modal wire:model="cannotDeleteItem" type="ghost">
                <x-slot name="title">
                    {{ __('Unable to Delete') }}
                </x-slot>

                <x-slot name="content">
                    {{ __('This amenity is currently active and cannot be deleted.') }}
                </x-slot>

                <x-slot name="footer">
                    <x-secondary-button wire:click="$set('cannotDeleteItem', false)" wire:loading.attr="disabled">
                        {{ __('OK') }}
                    </x-secondary-button>
                </x-slot>
            </x-dialog-modal>

        </div>
    </div>