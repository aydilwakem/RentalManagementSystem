<div class="min-h-[550px] container mx-auto p-8 bg-white rounded-lg">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Maintenance') }}
        </h2>
    </x-slot>

    <div class="py-6 px-10 mx-auto max-w-3xl border rounded-xl bg-white shadow-lg mt-6 mb-6 space-y-6">

    <!-- Back Button -->
    <div class="mx-auto max-w-2xl flex justify-end items-center">
        <button onclick="history.back()"
            class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none">
            <span class="leading-none translate-y-[-3px]">&times;</span>
        </button>
    </div>

    <!-- Title -->
    <h2 class="text-2xl md:text-3xl font-bold leading-tight text-gray-800 text-center">
        {{ $maintenance->name }}
    </h2>

    <!-- Description -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-1">Description</h3>
            <p class="text-gray-600 leading-relaxed">{{ $maintenance->description }}</p>
        </div>



        <!-- Priority Status -->
        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-1">Priority Status</h3>
            <p class="text-gray-600">{{ ucfirst($maintenance->priority_status) }}</p>
        </div>

        <!-- Reported At -->
        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-1">Reported At</h3>
            <p class="text-gray-600">{{ \Carbon\Carbon::parse($maintenance->reported_at)->format('Y-m-d') }}</p>
        </div>

        <!-- Resolved At -->
        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-1">Resolved At</h3>
            <p class="text-gray-600">{{ \Carbon\Carbon::parse($maintenance->resolved_at)->format('Y-m-d') }}</p>
        </div>


    </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-between space-x-4 pt-2">
            <!-- Edit -->
            <x-button type="button" icon="fas fa-pen-to-square"
                class="!text-black inline-flex items-center !bg-gray-200 hover:!bg-gray-300 font-medium rounded-lg text-sm px-6 py-2.5"
                wire:navigate href="{{ route('admin.edit-maintenance', ['maintenance' => $maintenance->id]) }}">
                Edit
            </x-button>

            <!-- Delete -->
            <x-button type="button" icon="fas fa-trash"
                class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-6 py-2.5"
                wire:click="deleteMaintenanceItem({{ $maintenance->id }})">
                Delete
            </x-button>
        </div>

    </div>

</div>
