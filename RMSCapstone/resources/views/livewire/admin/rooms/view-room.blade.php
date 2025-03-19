<div class="min-h-[550px] container mx-auto p-8 bg-white rounded-lg">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Room') }}
        </h2>
    </x-slot>

    <div class="py-3 px-8 mx-auto max-w-2xl border rounded-lg bg-white shadow-md">
        <!-- Room Name -->
        <h2 class="mb-2 text-xl text-center font-semibold leading-none text-gray-900 md:text-2xl">
            {{ $room->name }}
        </h2>

        <!-- Room Image -->
        <div class="mb-4">
            <img src="{{ asset('storage/' . $room->image) }}" alt="{{ $room->name }}"
                class="w-full h-64 object-cover rounded-lg shadow-md">
        </div>

        <!-- Room Category -->
        <div class="mb-2 mt-3 flex items-center gap-2">
            <h3 class="text-lg font-semibold text-gray-900 leading-none">Room Category:</h3>
            <p class="font-semibold text-gray-600 leading-none">
                {{ $room->category->name ?? 'No Category' }}
            </p>
        </div>


        <!-- Room Details -->
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Room Details</h3>
            <ul class="list-disc pl-5 text-gray-600">
                <li><strong>Ideal Guests:</strong> {{ $room->ideal_guest }}</li>
                <li><strong>Max Adults:</strong> {{ $room->max_adults }}</li>
                <li><strong>Max Kids:</strong> {{ $room->max_kids }}</li>
                <li><strong>Turnover Duration:</strong> {{ $room->turnover_duration }} hours</li>
                <li><strong>Room Status:</strong> {{ ucfirst($room->room_status) }}</li>
            </ul>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-between space-x-4 mt-3 mb-3">

            <!-- Edit -->
            <x-button type="button" icon="fas fa-pen-to-square"
                class="!text-black inline-flex items-center !bg-gray-200 hover:!bg-gray-300 font-medium rounded-lg text-sm px-5 py-2.5"
                wire:navigate href="{{ route('admin.edit-room', ['room' => $room->id]) }}">
                Edit
            </x-button>

            <!-- Delete -->
            <x-button type="button" icon="fas fa-trash"
                class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5"
                wire:click="confirmDelete({{ $room->id }})">
                Delete
            </x-button>

        </div>
        <!-- Delete Confirmation Modal -->
        <x-dialog-modal wire:model.live="confirmItemDelete">
            <x-slot name="title">
                {{ __('Delete Room') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to delete this item?') }}
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('confirmItemDelete', false)" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3" wire:click="deleteRoom({{ $room->id }})" wire:loading.attr="disabled">
                    {{ __('Delete Room') }}
                </x-danger-button>
            </x-slot>
        </x-dialog-modal>

    </div>

    <!-- Pass the id of the room to the livewire -->
    @livewire('admin.room-rates.view-individual-rates', ['roomId' => $room->id])

</div>