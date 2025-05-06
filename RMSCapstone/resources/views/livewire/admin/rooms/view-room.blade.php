<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Room') }}
        </h2>
    </x-slot>
    <div class="py-3">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 bg-white rounded-lg border shadow-md p-6">

            <!-- Back Button -->
            <div class="flex justify-end">
                <button onclick="history.back()"
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 relative flex items-center justify-center text-2xl focus:outline-none">
                    <span class="leading-none translate-y-[-3px]">&times;</span>
                </button>
            </div>

            <!-- Room Name -->
            <h2 class="mb-4 text-2xl font-semibold text-center text-gray-900">Room: {{ $room->name_number }}</h2>

            <!-- Room Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="grid grid-cols-1 gap-2">
                    @if (isset($room->images) && count($room->images) > 0)
                        <div class="w-full">
                            <img src="{{ asset('storage/' . $room->images[0]) }}"
                                class="w-full h-72 object-cover rounded border cursor-pointer" alt="Main Room Image"
                                onclick="openModal('{{ asset('storage/' . $room->images[0]) }}')">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                            @foreach (array_slice($room->images, 1) as $img)
                                <img src="{{ asset('storage/' . $img) }}"
                                    class="w-full h-44 object-cover rounded border cursor-pointer" alt="Room Image"
                                    onclick="openModal('{{ asset('storage/' . $img) }}')">
                            @endforeach
                        </div>
                    @else
                        <div class="w-full">
                            <img src="{{ asset('images/rms-default.png') }}"
                                class="w-full h-72 object-cover rounded border cursor-pointer" alt="Default Image"
                                onclick="openModal('{{ asset('images/rms-default.png') }}')">
                        </div>
                    @endif
                </div>
                <div id="imageModal" class="fixed z-10 inset-0 overflow-y-auto bg-black bg-opacity-80 hidden">
                    <div class="flex items-center justify-center min-h-screen">
                        <div class="modal-content">
                            <img id="modalImg" src="" class="max-w-full max-h-[80vh] rounded-md">
                            <div class="mt-4 text-center">
                                <button onclick="closeModal()"
                                    class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Room Details</h3>
                    <ul class="list-disc pl-5 text-gray-600 mb-3">
                        <li><strong>Room Category:</strong> {{ $room->category->name ?? 'N/A' }}</li>
                        <li><strong>Ideal Guests:</strong> {{ $room->ideal_guest }}</li>
                        <li><strong>Max Adults:</strong> {{ $room->max_adults }}</li>
                        <li><strong>Extra Person Charge:</strong> {{ $room->extra_person_charge }}</li>
                        <li><strong>Max Kids:</strong> {{ $room->max_kids }}</li>
                        <li><strong>Turnover Duration:</strong> {{ $room->turnover_duration }} hours</li>
                        <li><strong>Room Status:</strong> {{ ucfirst($room->property_status) }}</li>
                        <li><strong>Base Rate:</strong> {{ $room->amount }}</li>
                    </ul>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Amenities</h3>
                    @if ($room->features->isNotEmpty())
                        <div class="flex flex-wrap gap-2 mb-3">
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

                    <!-- Maximum Occupancy Rules -->
                    <h3 class="text-lg font-semibold mb-2">Maximum Occupancy Rules</h3>

                    @if ($room->occupancy_rules && is_array($room->occupancy_rules))
                        <div class="flex flex-col sm:flex-row flex-wrap gap-3">
                            @foreach ($room->occupancy_rules as $index => $rule)
                                <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg">
                                    <div class="flex justify-between items-center">
                                        <div class="text-sm">
                                            <p><strong>Adults:</strong> {{ $rule['adults'] }}</p>
                                            <p><strong>Kids:</strong> {{ $rule['kids'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p>No occupancy rules set.</p>
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
        </div>

        <!-- Delete Confirmation Modal -->
        <x-dialog-modal wire:model.live="confirmItemDelete">
            <x-slot name="title">
                {{ __('Delete Room') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to delete this item?') }}
            </x-slot>

            {{-- <x-slot name="footer">
                <x-secondary-button wire:click="$set('confirmItemDelete', false)" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3" wire:click="deleteRoom({{ $room->id }})"
                    wire:loading.attr="disabled">
                    {{ __('Delete Room') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to delete this item?') }}
            </x-slot> --}}

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
<script>
    function openModal(imageSrc) {
        const modal = document.getElementById('imageModal');
        const modalImg = document.getElementById('modalImg');
        modalImg.src = imageSrc;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Prevent background scroll
    }

    function closeModal() {
        const modal = document.getElementById('imageModal');
        modal.classList.add('hidden');
        document.body.style.overflow = ''; // Restore background scroll
    }
</script>

{{-- @livewire('admin.room-rates.view-individual-rates', ['roomId' => $room->id]) --}}
</div>
