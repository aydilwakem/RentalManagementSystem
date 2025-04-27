<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Room') }}
        </h2>
    </x-slot>
    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 bg-white rounded-lg border shadow-md p-6">

            <!-- Back Button -->
            <div class="flex justify-end mb-4">
                <button onclick="history.back()"
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none">
                    <span class="leading-none translate-y-[-3px]">&times;</span>
                </button>
            </div>

            <!-- Room Name -->
            <h2 class="mb-4 text-2xl font-semibold text-center text-gray-900">Room: {{ $room->name_number }}</h2>

            <!-- Room Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="mb-4 md:mb-0">
                    <img src="{{ asset($room->image ? 'storage/' . $room->image : 'images/rms-default.png') }}"
                        alt="{{ $room->name_number }}" class="w-full h-64 object-cover rounded-lg border">
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Room Details</h3>
                    <ul class="list-disc pl-5 text-gray-600 mb-3">
                        <li><strong>Room Category:</strong> {{ $room->category->name ?? 'N/A' }}</li>
                        <li><strong>Ideal Guests:</strong> {{ $room->ideal_guest }}</li>
                        <li><strong>Max Adults:</strong> {{ $room->max_adults }}</li>
                        <li><strong>Max Kids:</strong> {{ $room->max_kids }}</li>
                        <li><strong>Turnover Duration:</strong> {{ $room->turnover_duration }} hours</li>
                        <li><strong>Room Status:</strong> {{ ucfirst($room->property_status) }}</li>
                        <li><strong>Base Rate:</strong> {{ $room->amount }}</li>
                    </ul>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Amenities</h3>
                    @if ($room->features->isNotEmpty())
                    <div class="flex flex-wrap gap-2">
                        @foreach ($room->features as $feature)
                            <span
                                class="inline-flex items-center rounded-full bg-gray-200 px-3 py-1 text-sm font-semibold text-gray-700">
                                {{ $feature->name }}
                            </span>
                        @endforeach
                    </div>
                    @else
                        <p class="text-gray-500 mt-2">No amenities selected for this room.</p>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between space-x-4 mt-6">
                <x-button type="button" icon="fas fa-pen-to-square"
                    class="!bg-gray-200 !text-black hover:!bg-gray-300 focus:!ring-2 focus:!ring-gray-400 focus:!outline-none"
                    wire:navigate href="{{ route('admin.edit-room', ['room' => $room->id]) }}">
                    Edit
                </x-button>

                <x-button type="button" icon="fas fa-trash"
                    class="!bg-red-500 !text-white hover:!bg-red-600 focus:!ring-2 focus:!ring-red-400 focus:!outline-none"
                    wire:click="confirmDelete({{ $room->id }})">
                    Delete
                </x-button>
            </div>

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

                    <x-danger-button class="ms-3" wire:click="deleteRoom({{ $room->id }})"
                        wire:loading.attr="disabled">
                        {{ __('Delete Room') }}
                    </x-danger-button>
                </x-slot>
            </x-dialog-modal>

        </div>
    </div>

    {{-- @livewire('admin.room-rates.view-individual-rates', ['roomId' => $room->id]) --}}
</div>
