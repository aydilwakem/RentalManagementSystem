<div>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white mb-1">
            {{ __('View Service') }}
        </h2>
        <!-- Navigation -->
        <x-breadcrumbs :items="[
            ['label' => 'Services', 'url' => route('admin.services')],
            ['label' => 'View Service', 'url' => route('admin.view-service', ['service' => $service->id])],
        ]" />
    </x-slot>

    <!-- Body Container -->
    <div class="py-3">
        <div
            class="mx-auto max-w-7xl sm:px-6 lg:px-8 bg-white rounded-xl border shadow-md p-6 dark:bg-gray-700 dark:border-gray-600 dark:text-white">

            <div class="relative flex items-center mb-4">
                <!-- Service Name -->
                <h2 class="text-2xl font-bold text-gray-900 w-full text-center dark:text-white">Service:
                    {{ $service->name }}
                </h2>

                <!-- Back Button -->
                <button onclick="window.location.href='{{ route('admin.services') }}'"
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute right-0 translate-y-[-12px]">
                    <span class="leading-none translate-y-[-3px]">&times;</span>
                </button>
            </div>

            <!-- Promo Details -->
            <h3 class="text-lg font-bold text-green-800 mb-3 dark:text-green-300">Service Details</h3>
            <div class="bg-gray-50 rounded-lg p-6 mb-6 dark:bg-gray-600 dark:text-gray-200 border dark:border-gray-500">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2 text-gray-600 dark:text-gray-200">
                    <div><strong>Service Name:</strong> {{ $service->name }}</div>
                    <div><strong>Description:</strong> {{ $service->description ?? 'No description provided' }}</div>
                    <div><strong>Type:</strong> {{ ucfirst($service->type) }}</div>
                    <div><strong>Unit:</strong> {{ ucfirst($service->unit) }}</div>
                    <div><strong>Amount:
                        </strong>₱{{ number_format($service->amount, 2) }}
                    </div>
                    <div><strong>Service Status:</strong>
                        @if ($service->is_active)
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
            <div class="flex items-center justify-between space-x-4 mt-auto mb-3">
                <!-- Edit -->
                <x-ghost-button type="button" icon="fas fa-pen-to-square" wire:navigate
                    href="{{ route('admin.edit-service', ['service' => $service->id]) }}">
                    Edit
                </x-ghost-button>

                <!-- Delete -->
                <x-danger-button type="button" icon="fas fa-trash" wire:click="confirmDelete({{ $service->id }})">
                    Delete
                    </x-daanger-button>
            </div>

            {{-- Confirm Delete Modal --}}
            <x-dialog-modal wire:model.live="confirmItemDelete" type="danger">
                <x-slot name="title">
                    {{ __('Delete Service') }}
                </x-slot>

                <x-slot name="content">
                    {{ __('Are you sure you want to delete this service?') }}
                </x-slot>

                <x-slot name="footer">
                    <x-secondary-button wire:click="$set('confirmItemDelete', false)" wire:loading.attr="disabled">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-danger-button class="ms-3" wire:click="deleteService({{ $service->id }})"
                        wire:loading.attr="disabled">
                        {{ __('Delete Service') }}
                    </x-danger-button>
                </x-slot>
            </x-dialog-modal>

            {{-- Cannot Delete Modal --}}
            <x-dialog-modal wire:model="cannotDeleteItem" type="ghost">
                <x-slot name="title">
                    {{ __('Unable to Delete') }}
                </x-slot>

                <x-slot name="content">
                    {{ __('This service is active or in use and cannot be deleted.') }}
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