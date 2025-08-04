<div>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white mb-1">
            {{ __('View Room') }}
        </h2>
        <!-- Navigation -->
        <x-breadcrumbs :items="[
        ['label' => 'Rooms', 'url' => route('admin.rooms')],
        ['label' => 'View Room', 'url' => route('admin.view-room', ['room' => $room->id])],
    ]" />
    </x-slot>


    <!-- Body Container -->
    <div class="py-3 mb-4">
        <div
            class="mx-auto max-w-7xl sm:px-6 lg:px-8 bg-white rounded-xl border shadow-md p-6 dark:bg-gray-700 dark:border-gray-600">

            <div class="relative flex items-center mb-6">
                <!-- Room Name -->
                <h2 class="text-2xl font-bold text-gray-900 w-full text-center dark:text-white">Room:
                    {{ $room->name_number }}
                </h2>

                <!-- Back Button -->
                <button onclick="window.location.href='{{ route('admin.rooms') }}'"
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute right-0 translate-y-[-12px]">
                    <span class="leading-none translate-y-[-3px]">&times;</span>
                </button>
            </div>

            <!-- Room Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="grid grid-cols-1 gap-2">
                    <!-- Room Image Array -->
                    @if (isset($room->images) && count($room->images) > 0)
                        <div class="w-full">
                            <img src="{{ asset('uploads/' . $room->images[0]) }}"
                                class="w-full h-72 object-cover rounded border cursor-pointer" alt="Main Room Image"
                                onclick="openModal('{{ asset('uploads/' . $room->images[0]) }}')">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                            @foreach (array_slice($room->images, 1) as $img)
                                <img src="{{ asset('uploads/' . $img) }}"
                                    class="w-full h-44 object-cover rounded border cursor-pointer" alt="Room Image"
                                    onclick="openModal('{{ asset('uploads/' . $img) }}')">
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
                <!-- Image Popup View -->
                <div id="imageModal" class="fixed z-50 inset-0 overflow-y-auto bg-black bg-opacity-80 hidden">
                    <div class="flex items-center justify-center min-h-screen">
                        <div class=" relative modal-content">
                            <img id="modalImg" src="" class="max-w-full max-h-[80vh] rounded-md">
                            <button onclick="closeModal()"
                                class="absolute top-2 right-2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none">
                                <span class="leading-none translate-y-[-3px]">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Room Details -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-50">Room Details</h3>
                    <ul class="list-disc pl-5 text-gray-600 mb-3 dark:text-gray-300">
                        <li><strong>Room Category:</strong> {{ $room->category->name ?? 'N/A' }}</li>
                        <li><strong>Ideal Guests:</strong> {{ $room->ideal_guest }}</li>
                        <li><strong>Max Adults:</strong> {{ $room->max_adults }}</li>
                        <li><strong>Extra Person Charge:</strong> ₱{{ $room->extra_person_charge }}</li>
                        <li><strong>Max Kids:</strong> {{ $room->max_kids }}</li>
                        <li><strong>Turnover Duration:</strong> {{ $room->turnover_duration }} hours</li>
                        <li><strong>Room Status:</strong> {{ ucfirst($room->property_status) }}</li>
                        <li><strong>Base Rate:</strong> ₱{{ $room->amount }}</li>
                    </ul>

                    <div class="mb-1">
                        <div class="flex items-center">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-50 me-2">Free Breakfast</h3>
                            @if ($room->freebies)
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    Included <i class="fa-solid fa-check pl-2"></i>
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    Not Included
                                </span>
                            @endif
                        </div>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-50">Amenities</h3>
                    @if ($room->features->isNotEmpty())
                        <div class="flex flex-wrap gap-2 mb-3">
                            @foreach ($room->features as $feature)
                                <span
                                    class="inline-flex items-center rounded-full bg-gray-200 px-3 py-1 mt-1 text-sm font-semibold text-gray-700">
                                    {{ $feature->name }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 mt-2 dark:text-gray-200">No amenities selected for this room.</p>
                    @endif

                    <!-- Maximum Occupancy Rules -->
                    {{-- <h3 class="text-lg font-semibold mb-2">Maximum Occupancy Rules</h3>

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
                </div> --}}
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-between space-x-4 mt-6">
            <x-ghost-button type="button" icon="fas fa-pen-to-square" wire:navigate
                href="{{ route('admin.edit-room', ['room' => $room->id]) }}">
                Edit
            </x-ghost-button>

            <x-danger-button type="button" icon="fas fa-trash" wire:click="confirmDelete({{ $room->id }})">
                Delete
            </x-danger-button>
        </div>

        <!-- Delete Confirmation Modal -->
        <x-dialog-modal wire:model.live="confirmItemDelete" type="danger">
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

        {{-- Cannot Delete Modal --}}
        <x-dialog-modal wire:model="cannotDeleteItem" type="ghost">
            <x-slot name="title">
                {{ __('Unable to Delete') }}
            </x-slot>

            <x-slot name="content">
                {{ __('This room is currently in use and cannot be deleted.') }}
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('cannotDeleteItem', false)" wire:loading.attr="disabled">
                    {{ __('OK') }}
                </x-secondary-button>
            </x-slot>
        </x-dialog-modal>
    </div>
</div>

<!-- Image Modal Script -->
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

@livewire('admin.room-rates.view-individual-rates', ['roomId' => $room->id])

</div>