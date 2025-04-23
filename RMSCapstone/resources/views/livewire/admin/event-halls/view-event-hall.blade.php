<div class="min-h-[550px] container mx-auto p-12 bg-white rounded-lg">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Event Hall') }}
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

        <!-- Event Hall Name -->
        <h2 class="mb-2 text-xl text-center font-semibold leading-none text-gray-900 md:text-2xl">
            {{ $eventHall->name }}
        </h2>

        <!-- Event Hall Image -->
        <div class="mb-4">
            <img src="{{ asset($eventHall->image ? 'storage/' . $eventHall->image : 'images/rms-default.png') }}"
                alt="{{ $eventHall->name }}" class="w-full h-64 object-cover rounded-lg shadow-md">
        </div>

        <!-- Event Hall Details -->
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Event Hall Details</h3>
            <ul class="list-disc pl-5 text-gray-600">
                <li><strong>Description:</strong>
                    @if(!empty($eventHall->description))
                    {{ $eventHall->description }}
                    @else
                    <em>No description provided.</em>
                    @endif
                </li>
                <li><strong>Amount:</strong> {{ $eventHall->amount }}</li>
                <li><strong>Capacity:</strong> {{ $eventHall->capacity }}</li>
                <li><strong>Extra Charge Per Hour:</strong> {{ $eventHall->extra_charge_per_hour }}</li>
                <li><strong>Hall Status:</strong> {{ ucfirst($eventHall->property_status) }}</li>
            </ul>

            <!-- Room Amenities -->
            <h3 class="text-lg font-semibold text-gray-900">Amenities</h3>
            @if ($eventHall->features->isNotEmpty())
            <ul class="list-disc list-inside mt-2 text-gray-700">
                @foreach ($eventHall->features as $feature)
                <li>{{ $feature->name }}</li>
                @endforeach
            </ul>
            @else
            <p class="text-gray-500 mt-2">No features selected for this room.</p>
            @endif

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
                    wire:click="confirmDelete({{ $eventHall->id }})">
                    Delete
                </x-button>

            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <x-dialog-modal wire:model.live="confirmItemDelete">
            <x-slot name="title">
                {{ __('Delete Event Hall') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to delete this item?') }}
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('confirmItemDelete', false)" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3" wire:click="deleteEventHall" wire:loading.attr="disabled">
                    {{ __('Delete Event Hall') }}
                </x-danger-button>
            </x-slot>
        </x-dialog-modal>

        {{-- Cannot Delete Modal --}}
        <x-dialog-modal wire:model="cannotDeleteItem">
            <x-slot name="title">
                {{ __('Unable to Delete') }}
            </x-slot>

            <x-slot name="content">
                {{ __('This event hall is currently in use and cannot be deleted.') }}
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('cannotDeleteItem', false)" wire:loading.attr="disabled">
                    {{ __('OK') }}
                </x-secondary-button>
            </x-slot>
        </x-dialog-modal>
    </div>