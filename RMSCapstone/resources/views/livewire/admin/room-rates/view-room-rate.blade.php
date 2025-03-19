<div class="min-h-[550px] container mx-auto p-8 bg-white rounded-lg">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Room Rate') }}
        </h2>
    </x-slot>

    <div class="py-3 px-8 mx-auto max-w-2xl border rounded-lg bg-white shadow-md">

        <!-- Back Button -->
        <div class="mx-auto max-w-2xl lg:py-2 flex justify-end items-center">
            <button onclick="history.back()"
                class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none">
                <span class="leading-none translate-y-[-3px]">&times;</span>
            </button>
        </div>

        <!-- Room Rate Name -->
        <h2 class="mb-2 text-xl text-center font-semibold leading-none text-gray-900 md:text-2xl">
            {{ $roomRate->name }}
        </h2>

        <!-- Room Information -->
        <div class="mb-2 mt-3 flex items-center gap-2">
            <h3 class="text-lg font-semibold text-gray-900 leading-none">Room:</h3>
            <p class="font-semibold text-gray-600 leading-none">
                {{ $roomRate->room->name ?? 'No Room Assigned' }}
            </p>
        </div>

        <!-- Rate Details -->
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Rate Details</h3>
            <ul class="list-disc pl-5 text-gray-600">
                <li><strong>Start Date:</strong> {{ $roomRate->start_date }}</li>
                <li><strong>End Date:</strong> {{ $roomRate->end_date }}</li>
                <li><strong>Amount:</strong> ₱{{ number_format($roomRate->amount, 2) }}</li>
                <li><strong>Extra Person Charge:</strong> ₱{{ number_format($roomRate->extra_person_charge, 2) }}</li>
                <li><strong>Extended Stay Charge Per Hour:</strong> ₱{{ number_format($roomRate->extended_stay_charge_per_hr, 2) }}</li>
            </ul>
        </div>

        <!-- Rate Type & Description -->
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Rate Type</h3>
            <p class="font-light text-gray-500">{{ ucfirst($roomRate->rate_type) }}</p>
        </div>
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Description</h3>
            <p class="font-light text-gray-500">{{ $roomRate->description }}</p>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-between space-x-4 mt-3 mb-3">
            <!-- Edit -->
            <x-button type="button" icon="fas fa-pen-to-square"
                class="!text-black inline-flex items-center !bg-gray-200 hover:!bg-gray-300 font-medium rounded-lg text-sm px-5 py-2.5"
                wire:navigate href="{{ route('admin.edit-room-rate', ['roomRate' => $roomRate->id]) }}">
                Edit
            </x-button>

            <!-- Delete -->
            <x-button type="button" icon="fas fa-trash"
                class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5"
                wire:click="deleteRoomRate({{ $roomRate->id }})">
                Delete
            </x-button>
        </div>
    </div>
</div>
