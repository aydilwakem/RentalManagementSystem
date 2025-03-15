<div class="min-h-[550px] container mx-auto p-12 bg-white rounded-lg">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Event Hall') }}
        </h2>
    </x-slot>

    <div class="py-3 px-8 mx-auto max-w-2xl border rounded-lg bg-white shadow-md">
        <!-- Event Hall Name -->
        <h2 class="mb-2 text-xl text-center font-semibold leading-none text-gray-900 md:text-2xl">
            {{ $eventHall->name }}
        </h2>

        <!-- Event Hall Image -->
        <div class="mb-4">
            <img src="{{ asset('storage/' . $eventHall->image) }}" alt="{{ $eventHall->name }}"
                class="w-full h-64 object-cover rounded-lg shadow-md">
        </div>

        <!-- Event Hall Details -->
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Event Hall Details</h3>
            <ul class="list-disc pl-5 text-gray-600">
                <li><strong>Description:</strong> {{ $eventHall->description }}</li>
                <li><strong>Amount:</strong> {{ $eventHall->amount }}</li>
                <li><strong>Capacity:</strong> {{ $eventHall->capacity }}</li>
                <li><strong>Extra Charge Per Hour:</strong> {{ $eventHall->extra_charge_per_hr }}</li>
            </ul>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between space-x-4 mt-8 mb-3">

                <!-- Edit -->
                <x-button type="button" icon="fas fa-pen-to-square"
                    class="!text-black inline-flex items-center !bg-gray-200 hover:!bg-gray-300 font-medium rounded-lg text-sm px-5 py-2.5"
                    wire:navigate href="{{ route('admin.edit-event-hall', ['eventHall' => $eventHall->id]) }}">
                    Edit
                </x-button>

                <!-- Delete -->
                <x-button type="button" icon="fas fa-trash"
                    class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5"
                    wire:click="deleteEventHall({{ $eventHall->id }})">
                    Delete
                </x-button>

            </div>
        </div>

    </div>
