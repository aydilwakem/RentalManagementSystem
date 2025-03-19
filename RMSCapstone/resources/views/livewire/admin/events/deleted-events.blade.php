<div class="min-h-[550px] container mx-auto p-6 ">
    @if ($deletedEvents->isEmpty())
        <!-- Empty Page Message -->
        <div class="text-center py-10">
            <p class="text-gray-500 text-lg font-semibold">No deleted events yet.</p>
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

                            <!-- Event Name -->
                            <th scope="col" class="px-4 py-3 text-left">Event Name</th>

                            <!-- Event Category -->
                            <th scope="col" class="px-4 py-3 text-left">Event Category</th>

                            <!-- Actions -->
                            <th scope="col" class="px-4 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @foreach ($deletedEvents as $event)
                            <tr class="border-b">
                                <td class="px-4 py-3 font-medium text-gray-900 text-left">{{ $fakeIDs[$event->id] ?? 'RCT-???' }}</td>
                                <td class="px-4 py-3 text-left">{{ $event->name }}</td>
                                <td class="px-4 py-3 text-left">{{ $event->category->name }}</td>
                                <td class="px-4 py-3 text-center">
                                    <x-button wire:click="restoreEvent({{ $event->id }})">
                                        Restore
                                    </x-button>
                                    <!-- Delete Forever Button -->
                                    <x-button wire:click="deleteEventForever({{ $event->id }})"
                                        class="!bg-red-500 hover:!bg-red-600 text-white font-semibold px-4 py-2 rounded"
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
