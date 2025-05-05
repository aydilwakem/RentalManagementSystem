<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Maintenance') }}
        </h2>
    </x-slot>

    <div class="py-6 px-10 mx-auto max-w-4xl border rounded-xl bg-white shadow-lg mt-6 space-y-5">
        <div class="flex justify-end">
            <button onclick="history.back()"
                class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none">
                <span class="leading-none translate-y-[-3px]">&times;</span>
            </button>
        </div>

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
                        <span class="px-2 py-1 bg-green-600 text-white rounded">{{ ucfirst($maintenance->priority_status) }}</span>
                    @elseif($maintenance->priority_status === 'routine')
                        <span class="px-2 py-1 bg-blue-600 text-white rounded">{{ ucfirst($maintenance->priority_status) }}</span>
                    @elseif($maintenance->priority_status === 'urgent')
                        <span class="px-2 py-1 bg-yellow-500 text-white rounded">{{ ucfirst($maintenance->priority_status) }}</span>
                    @elseif($maintenance->priority_status === 'emergency')
                        <span class="px-2 py-1 bg-red-600 text-white rounded">{{ ucfirst($maintenance->priority_status) }}</span>
                    @else
                        <span class="px-2 py-1 bg-gray-400 text-white rounded">Unknown</span>
                    @endif
                </p>
            </div>

        </div>

        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Report and Resolution Dates</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border">
                    <thead class="bg-gray-100">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                Reported At
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
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
                                    <span class="text-gray-500 italic">Unresolved</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex items-center justify-between space-x-4 pt-4">
            <x-button type="button" icon="fas fa-pen-to-square"
                class="!text-black inline-flex items-center !bg-gray-200 hover:!bg-gray-300 font-medium rounded-lg text-sm px-6 py-2.5"
                wire:navigate href="{{ route('admin.edit-maintenance', ['maintenance' => $maintenance->id]) }}">
                Edit
            </x-button>

            <x-button type="button" icon="fas fa-trash"
                class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-6 py-2.5"
                wire:click="confirmDelete({{ $maintenance->id }})" wire:loading.attr="disabled">
                Delete
            </x-button>
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
