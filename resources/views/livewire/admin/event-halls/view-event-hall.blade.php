<div>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white mb-1">
            {{ __('View Event Hall') }}
        </h2>
        <!-- Navigation -->
        <x-breadcrumbs :items="[
            ['label' => 'Event Halls', 'url' => route('admin.event-halls')],
            ['label' => 'View Event Hall', 'url' => route('admin.view-event-hall', ['eventHall' => $eventHall->id])],
        ]" />
    </x-slot>

    <!-- Body Container -->
    <div class="py-3">
        <div
            class="mx-auto max-w-7xl sm:px-6 lg:px-8 bg-white rounded-xl border shadow-md p-6 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">

            <div class="relative flex items-center mb-6">
                <!-- Room Name -->
                <h2 class="text-2xl font-bold text-gray-900 w-full text-center dark:text-white">Event Hall: {{ $eventHall->name_number }}
                </h2>

                <!-- Back Button -->
                <button onclick="window.location.href='{{ route('admin.event-halls') }}'"
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute right-0 translate-y-[-12px]">
                    <span class="leading-none translate-y-[-3px]">&times;</span>
                </button>
            </div>

            <!-- Event Hall Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Event Hall Image -->
                <div class="grid grid-cols-1 gap-2">
                    @if (isset($eventHall->images) && count($eventHall->images) > 0)
                        <div class="w-full">
                            <img src="{{ asset('storage/' . $eventHall->images[0]) }}"
                                class="w-full h-72 object-cover rounded border cursor-pointer"
                                alt="Main eventHall Image"
                                onclick="openModal('{{ asset('storage/' . $eventHall->images[0]) }}')">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                            @foreach (array_slice($eventHall->images, 1) as $img)
                                <img src="{{ asset('storage/' . $img) }}"
                                    class="w-full h-44 object-cover rounded border cursor-pointer" alt="eventHall Image"
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

                <!-- Event Hall Details -->
                <div
                    class="bg-white p-6 rounded-xl space-y-3 dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500 border">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white">Event Hall Details</h3>
                    <ul class="list-disc pl-5 space-y-2 text-gray-700 dark:text-gray-200">
                        <li><strong>Description:</strong>
                            @if (!empty($eventHall->description))
                                <span
                                    class="block ml-2 text-gray-600 dark:text-gray-200">{{ $eventHall->description }}</span>
                            @else
                                <em class="text-gray-500 ml-2 dark:text-gray-200">No description provided.</em>
                            @endif
                        </li>
                        <li><strong>Rate:</strong> ₱{{ number_format($eventHall->amount, 2) }}</li>
                        <li><strong>Capacity:</strong> {{ $eventHall->capacity }} guests</li>
                        <li><strong>Extra Charge Per Hour:</strong>
                            ₱{{ number_format($eventHall->extra_charge_per_hour, 2) }}</li>
                        <li><strong>Status:</strong> {{ ucfirst($eventHall->property_status) }}</li>
                    </ul>

                    <div>
                        <h3 class="text-xl font-semibold text-gray-800 mt-4 dark:text-white">Amenities</h3>
                        @if ($eventHall->features->isNotEmpty())
                            <ul class="list-disc list-inside mt-2 text-gray-700">
                                @foreach ($eventHall->features as $feature)
                                    <span
                                        class="inline-flex items-center rounded-full bg-gray-200 px-3 py-1 text-sm font-semibold text-gray-700">
                                        {{ $feature->name }}
                                    </span>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500 mt-2 dark:text-gray-200">No features selected for this event hall.
                            </p>
                        @endif
                    </div>
                </div>
            </div>
            <!-- Action Buttons -->
            <div class="flex items-center justify-between space-x-4 mt-6 mb-3">
                <!-- Edit -->
                <x-ghost-button type="button" icon="fas fa-pen-to-square" wire:navigate
                    href="{{ route('admin.edit-event-hall', ['eventHall' => $eventHall->id]) }}">
                    Edit
                </x-ghost-button>

                <!-- Delete -->
                <x-danger-button type="button" icon="fas fa-trash" wire:click="confirmDelete({{ $eventHall->id }})">
                    Delete
                </x-danger-button>
            </div>
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
</div>
