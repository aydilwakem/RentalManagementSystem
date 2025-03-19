<div class="min-h-[550px] container mx-auto p-6 ">
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
                                <td class="px-4 py-3 text-left font-medium text-gray-900">{{ $fakeIDs[$room->id] ?? 'RM-???' }}</td>
                                <td class="px-4 py-3 text-left">{{ $room->name }}</td>
                                <td class="px-4 py-3 text-left">{{ $room->category->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3 space-x-2 text-center">
                                    <x-button wire:click="restoreRoom({{ $room->id }})">
                                        Restore
                                    </x-button>
                                    <!-- Delete Forever Button -->
                                    <x-button wire:click="deleteRoomForever({{ $room->id }})"
                                        class="!bg-red-500 hover:!bg-red-600 focus:outline-none focus:ring-2 focus:!ring-red-500 text-white font-semibold px-4 py-2 rounded"
                                        onclick="return confirm('Are you sure you want to permanently delete this room? This action cannot be undone.')">
                                        Delete Forever
                                    </x-button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
