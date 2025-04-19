<div class="min-h-[550px] container mx-auto p-6 ">

    <!-- Back Button -->
    <div class="mb-4">
        <button onclick="window.history.back();"
            class="inline-flex items-center text-gray-700 hover:text-gray-900 font-semibold focus:outline-none">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 18l-6-6 6-6" />
            </svg>
            Back to Rooms
        </button>
    </div>

    @if ($deletedRooms->isEmpty())
        <!-- Empty Page Message -->
        <div class="text-center py-10">
            <p class="text-gray-500 text-lg font-semibold">No deleted rooms yet.</p>
        </div>
    @else
        {{-- Display Session Message --}}
        @if (session('message'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                class="fixed top-4 left-1/2 transform -translate-x-1/2 px-4 py-2 rounded-lg shadow-lg                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    {{ session('alert-type') === 'success' ? 'bg-red-500 text-white' : 'bg-green-500 text-white' }}">
                {{ session('message') }}
            </div>
        @endif
        <div>
            <!-- Table -->
            <div class="bg-white rounded-lg shadow-md overflow-x-auto border">
                <!-- Table Body-->
                <table class="w-full text-left">
                    <thead class="text-sm text-gray-700 bg-gray-200">
                        <tr>
                            <!-- ID -->
                            <th scope="col" class="px-4 py-3 text-left">ID</th>

                            <!-- Room Name -->
                            <th scope="col" class="px-4 py-3 text-left">Name</th>

                            <!-- Room Category -->
                            <th scope="col" class="px-4 py-3 text-left">Category</th>

                            <!-- Actions -->
                            <th scope="col" class="px-4 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @foreach ($deletedRooms as $room)
                            <tr class="border-b">
                                <td class="px-4 py-3 text-left font-medium text-gray-900">{{ $fakeIDs[$room->id] ?? 'RM-???' }}
                                </td>
                                <td class="px-4 py-3 text-left">{{ $room->name_number }}</td>
                                <td class="px-4 py-3 text-left">{{ $room->category->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3 space-x-2 text-center">
                                    <x-button wire:click="restoreRoom({{ $room->id }})">
                                        Restore
                                    </x-button>
                                    <!-- Delete Forever Button -->
                                    <x-button wire:click="confirmDeleteForever({{ $room->id }})"
                                        class="!bg-red-500 hover:!bg-red-600 focus:outline-none focus:ring-2 focus:!ring-red-500 text-white font-semibold px-4 py-2 rounded">
                                        Delete Forever
                                    </x-button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Delete Confirmation Modal -->
            <x-dialog-modal wire:model.live="confirmItemDelete">
                <x-slot name="title">
                    {{ __('Delete Room Forever') }}
                </x-slot>

                <x-slot name="content">
                    {{ __('Are you sure you want to permanently delete this item?') }}
                </x-slot>

                <x-slot name="footer">
                    <x-secondary-button wire:click="$set('confirmItemDelete', false)" wire:loading.attr="disabled">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-danger-button class="ms-3" wire:click="deleteRoomForever({{ $room->id }})"
                        wire:loading.attr="disabled">
                        {{ __('Delete Room') }}
                    </x-danger-button>
                </x-slot>
            </x-dialog-modal>
        </div>
    @endif
</div>