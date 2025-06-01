<div>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View  Maintenance Report') }}
        </h2>
    </x-slot>

    <!-- Body Container -->
    <div class="py-3">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 bg-white rounded-xl border shadow-md p-6">

            <div class="relative flex items-center mb-6">
                <!-- Back Button -->
                <button onclick="window.location.href='{{ route('admin.maintenances') }}'"
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute right-0 top-0 translate-y-[-12px]">
                    <span class="leading-none translate-y-[-3px]">&times;</span>
                </button>
            </div>

            <!-- Maintenance Details -->
            <h2 class="text-2xl md:text-3xl font-bold leading-tight text-gray-800 text-center transform -translate-y-2">
                {{ $maintenance->name }}
            </h2>

            <div class="space-y-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Assigned Property</h3>
                    <p class="text-gray-600">{{ $maintenance->property->name_number ?? 'No Assigned Property' }}</p>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Description</h3>
                    <p class="text-gray-600 leading-relaxed">{{ $maintenance->description }}</p>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">Priority Status</h3>
                    <p class="text-gray-600">
                        @if ($maintenance->priority_status === 'planned')
                            <span
                                class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-gray-100 text-gray-600">
                                Planned
                            </span>
                        @elseif($maintenance->priority_status === 'routine')
                            <span
                                class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-cyan-100 text-cyan-600">
                                Routine
                            </span>
                        @elseif($maintenance->priority_status === 'urgent')
                            <span
                                class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-600">
                                Urgent
                            </span>
                        @elseif($maintenance->priority_status === 'emergency')
                            <span
                                class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-red-100 text-red-600">
                                Emergency
                            </span>
                        @endif
                    </p>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Report and Resolution Dates</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Reported At
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Resolved At
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $maintenance->reported_at->format('F j, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if ($maintenance->resolved_at)
                                            {{ $maintenance->resolved_at->format('F j, Y') }}
                                        @else
                                            <span class="text-gray-500 italic">Not yet resolved</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <div class="flex items-center justify-between space-x-4 pt-4 mt-auto">
                <x-ghost-button type="button" icon="fas fa-pen-to-square"
                    wire:navigate href="{{ route('admin.edit-maintenance', ['maintenance' => $maintenance->id]) }}">
                    Edit
                </x-ghost-button>

                <x-danger-button type="button" icon="fas fa-trash"
                    wire:click="confirmDelete({{ $maintenance->id }})" wire:loading.attr="disabled">
                    Delete
                </x-danger-button>
            </div>
        </div>

        <x-dialog-modal wire:model.live="confirmItemDelete">
            <x-slot name="title">
                {{ __('Delete Maintenance') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to delete this item?') }}
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('confirmItemDelete', false)" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3" wire:click="deleteMaintenanceItem({{ $maintenance->id }})"
                    wire:loading.attr="disabled">
                    {{ __('Delete Maintenance') }}
                </x-danger-button>
            </x-slot>
        </x-dialog-modal>
    </div>
</div>
