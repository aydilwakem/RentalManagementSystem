<div class="min-h-[550px] container mx-auto p-6 max-w-full">
    @if ($allRooms->isEmpty())
    <!-- Empty Page Message -->
    <div class="text-center py-10">
        <p class="text-gray-500 text-lg font-semibold">No rooms yet.<br> Click "Create Room" to add a new room.</p>
        <x-button class="mt-4" href="{{ route('admin.create-room') }}" icon="fas fa-plus">
            Create Room
        </x-button>
    </div>
    @else
    {{-- Display Session Message --}}
    @if (session('message'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
        class="fixed top-4 left-1/2 transform -translate-x-1/2 px-4 py-2 rounded-lg shadow-lg
                                                                                                        {{ session('alert-type') === 'success' ? 'bg-red-500 text-white' : 'bg-green-500 text-white' }}">
        {{ session('message') }}
    </div>
    @endif
    <div>
        <div class="flex items-center justify-between">
            <!-- Label and Confirm Button -->
            @can('room-create')
            <div class="flex justify-between items-center mb-4">
                <x-button icon="fas fa-plus" onclick="window.location.href='{{ route('admin.create-room') }}'">
                    New Room
                </x-button>
            </div>
            @endcan
            <!-- Deleted Rooms (Restore and Delete Forever -->
            @can('room-soft-delete')
            <x-button class=" mb-4 !bg-gray-600 hover:!bg-gray-700 focus:ring focus:!ring-gray-600 focus:!ring-offset-2"
                icon="fas fa-trash" href="{{ route('admin.deleted-rooms') }}">
                Deleted Rooms
            </x-button>
            @endcan
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-md overflow-x-auto border">
        <!-- Header-->
        <div class="flex items-center justify-between d p-4">
            <div class="flex">
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg aria-hidden="true" class="w-5 h-5 text-gray-500 " fill="currentColor" viewbox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    {{-- Search --}}
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 "
                        placeholder="Search" required="">
                </div>
            </div>

            {{-- Status Type --}}
            <div class="flex space-x-3">
                <div class="flex space-x-3 items-center">
                    <label class="w-40 text-sm font-medium text-gray-900">Room Status:</label>
                    <select wire:model.live="statusFilter"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="">All</option>
                        <option value="available">Available</option>
                        <option value="booked">Booked</option>
                        <option value="out_of_service">Out of Service</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Table Body-->
        <div class="overflow-x-auto">
            <table class="min-w-full text-left">
                <thead class="text-sm text-gray-700 bg-gray-200">
                    <tr>
                        {{-- ID --}}
                        <th scope="col" class="px-4 py-3" wire:click="setSortBy('id')">
                            <button class="flex items-center">
                                ID
                                @if ($sortBy !== 'id')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                                @else
                                @if ($sortDir == 'ASC')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                </svg>
                                @else
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                                @endif
                                @endif
                            </button>
                        </th>

                        {{-- Name --}}
                        <th scope="col" class="px-4 py-3" wire:click="setSortBy('name_number')">
                            <button class="flex items-center">
                                Name
                                @if ($sortBy !== 'name_number')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                                @else
                                @if ($sortDir == 'ASC')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                </svg>
                                @else
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                                @endif
                                @endif
                            </button>
                        </th>

                        {{-- Room Category --}}
                        <th scope="col" class="px-4 py-3" wire:click="setSortBy('room_category_id')">
                            <button class="flex items-center">
                                Room Category
                                @if ($sortBy !== 'room_category_id')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                                @else
                                @if ($sortDir == 'ASC')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                </svg>
                                @else
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                                @endif
                                @endif
                            </button>
                        </th>

                        {{-- Ideal Guest --}}
                        <th scope="col" class="px-4 py-3" wire:click="setSortBy('ideal_guest')">
                            <button class="flex items-center">
                                Ideal Guest
                                @if ($sortBy !== 'ideal_guest')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                                @else
                                @if ($sortDir == 'ASC')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                </svg>
                                @else
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                                @endif
                                @endif
                            </button>
                        </th>
                        {{-- Max Adults --}}
                        <th scope="col" class="px-4 py-3" wire:click="setSortBy('max_adults')">
                            <button class="flex items-center">
                                Max Adults
                                @if ($sortBy !== 'max_adults')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                                @else
                                @if ($sortDir == 'ASC')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                </svg>
                                @else
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                                @endif
                                @endif
                            </button>
                        </th>

                        {{-- Max Kids --}}
                        <th scope="col" class="px-4 py-3" wire:click="setSortBy('max_kids')">
                            <button class="flex items-center">
                                Max Kids
                                @if ($sortBy !== 'max_kids')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                                @else
                                @if ($sortDir == 'ASC')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                </svg>
                                @else
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                                @endif
                                @endif
                            </button>
                        </th>

                        {{-- Turnover Duration --}}
                        <th scope="col" class="px-4 py-3" wire:click="setSortBy('turnover_duration')">
                            <button class="flex items-center">
                                Turnover Duration
                                @if ($sortBy !== 'turnover_duration')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                                @else
                                @if ($sortDir == 'ASC')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                </svg>
                                @else
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                                @endif
                                @endif
                            </button>
                        </th>

                        {{-- Status --}}
                        <th scope="col" class="px-4 py-3 ">Status</th>

                        {{-- Actions --}}
                        <th scope="col" class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @forelse ($rooms as $room)
                    <tr class="border-b">
                        <th scope="row" class="font-medium text-gray-900 text-center ">
                            {{ $fakeIDs[$room->id] ?? 'RM-???' }}
                        </th>
                        <td class="px-4 py-3">{{ $room->name_number }}</td>
                        <td class="px-4 py-3">{{ $room->category->name ?? 'N/A' }}</td>
                        <td class="px-4 py-3">{{ $room->ideal_guest }}</td>
                        <td class="px-4 py-3">{{ $room->max_adults }}</td>
                        <td class="px-4 py-3">{{ $room->max_kids }}</td>
                        <td class="px-4 py-3">{{ $room->turnover_duration }}</td>
                        <td class="px-4 py-3">
                            @if ($room->property_status === 'available')
                            <span class="px-2 py-1 bg-green-700 text-white rounded-md">Available</span>
                            @elseif($room->property_status === 'booked')
                            <span class="px-2 py-1 bg-yellow-500 text-white rounded">Booked</span>
                            @elseif($room->property_status === 'out_of_service')
                            <span class="px-2 py-1 bg-red-500 text-white rounded">Out of Service</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 flex items-center justify-center space-x-3">
                            <!-- View Icon -->
                            @can('room-view')
                            <i class="fas fa-eye text-gray-700 hover:text-blue-600 cursor-pointer" wire:navigate
                                href="{{ route('admin.view-room', ['room' => $room->id]) }}">
                            </i>
                            @endcan

                            <!-- Edit Icon -->
                            @can('room-edit')
                            <i class="fas fa-edit text-gray-700 hover:text-yellow-600 cursor-pointer" wire:navigate
                                href="{{ route('admin.edit-room', ['room' => $room->id]) }}">
                            </i>
                            @endcan

                            <!-- Delete Icon -->
                            @can('room-delete')
                            <i class="fas fa-trash-alt text-gray-700 hover:text-red-600 cursor-pointer"
                                wire:click="confirmDelete({{ $room->id }})" wire:loading.attr="disabled">
                            </i>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="15" class="text-center py-10 text-gray-500">
                            No rooms found matching this status.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{-- Per Page --}}
        <div class="py-4 px-3">
            <div class="flex ">
                <div class="flex space-x-4 items-center mb-3">
                    <label class="w-32 text-sm font-medium text-gray-900">Per Page</label>
                    <select wire:model.live='perPage'
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>
            {{ $rooms->links() }}
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

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('confirmItemDelete', false)" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click="deleteRoom" wire:loading.attr="disabled">
                {{ __('Delete Room') }}
            </x-danger-button>
        </x-slot>
    </x-dialog-modal>
    @endif
</div>