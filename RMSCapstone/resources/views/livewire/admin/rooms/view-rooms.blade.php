<div class="min-h-[550px] container mx-auto p-6 ">

    <!-- Label and Confirm Button -->
    <div class="flex justify-between items-center mb-4">
        <x-button onclick="window.location.href='{{ route('admin.create-room') }}'">
            + Create Room
        </x-button>
    </div>

    <!-- Table -->
    <div class="bg-white p-4 rounded-lg shadow-md overflow-x-auto border">
        <div class="flex items-center justify-between d p-4">
            <div class="flex">
                <div class=" w-full">
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
                    <label class="w-40 text-sm font-medium text-gray-900">User Type :</label>
                    <select wire:model.live="statusFilter"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="">All</option>
                        <option value="Available">Available</option>
                        <option value="Booked">Booked</option>
                        <option value="Out of Service">Out of Service</option>
                    </select>
                </div>
            </div>
        </div>
        <table class="w-full">
            <thead class="text-sm text-gray-700 uppercase bg-gray-200">
                <tr>
                    {{-- ID --}}
                    <th scope="col" class="px-4 py-3" wire:click="setSortBy('id')">
                        <button class="flex items-center">
                            ID
                            @if ($sortBy !== 'id')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            @else
                                @if($sortDir == 'ASC')
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" class="size-4 ml-1">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" class="size-4 ml-1">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                    </svg>
                                @endif
                            @endif
                        </button>
                    </th>

                    {{-- Name --}}
                    <th scope="col" class="px-4 py-3" wire:click="setSortBy('name')">
                        <button class="flex items-center">
                            Name
                            @if ($sortBy !== 'name')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            @else
                                @if($sortDir == 'ASC')
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" class="size-4 ml-1">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" class="size-4 ml-1">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
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
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            @else
                                @if($sortDir == 'ASC')
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" class="size-4 ml-1">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" class="size-4 ml-1">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
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
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            @else
                                @if($sortDir == 'ASC')
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" class="size-4 ml-1">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" class="size-4 ml-1">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
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
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            @else
                                @if($sortDir == 'ASC')
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" class="size-4 ml-1">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" class="size-4 ml-1">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
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
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            @else
                                @if($sortDir == 'ASC')
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" class="size-4 ml-1">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" class="size-4 ml-1">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
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
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            @else
                                @if($sortDir == 'ASC')
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" class="size-4 ml-1">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" class="size-4 ml-1">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                    </svg>
                                @endif
                            @endif
                        </button>
                    </th>

                    {{-- Status--}}
                    <th scope="col" class="px-4 py-3 ">Status</th>

                    {{-- Actions--}}
                    <th scope="col" class="px-4 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @foreach($rooms as $room)
                    <tr class="border-b">
                        <th scope="row" class="font-medium text-gray-900 text-center">
                            {{ $room->id }}
                        </th>
                        <td class="p-2">{{ $room->name }}</td>
                        <td class="p-2">{{ $room->category->name ?? 'N/A' }}</td>
                        <td class="p-2">{{ $room->ideal_guest }}</td>
                        <td class="p-2">{{ $room->max_adults }}</td>
                        <td class="p-2">{{ $room->max_kids }}</td>
                        <td class="p-2">{{ $room->turnover_duration }}</td>
                        <td class="p-2">
                            @if($room->room_status === 'Available')
                                <span class="px-2 py-1 bg-green-700 text-white rounded-md">Available</span>
                            @elseif($room->room_status === 'Booked')
                                <span class="px-2 py-1 bg-yellow-500 text-white rounded">Booked</span>
                            @elseif($room->room_status === 'Out of Service')
                                <span class="px-2 py-1 bg-red-500 text-white rounded">Out of Service</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 flex items-center justify-center space-x-3">
                            <!-- View Icon -->
                            <i class="fas fa-eye text-gray-700 hover:text-yellow-600 cursor-pointer" wire:navigate
                                href="{{ route('admin.view-room', ['room' => $room->id]) }}">
                            </i>
                            <!-- Edit Icon -->
                            <i class="fas fa-edit text-gray-700 hover:text-blue-600 cursor-pointer" wire:navigate
                                href="{{ route('admin.edit-room', ['room' => $room->id]) }}">
                            </i>
                            <!-- Delete Icon -->
                            <i class="fas fa-trash-alt text-gray-700 hover:text-red-600 cursor-pointer"
                                wire:click="deleteRoom({{ $room->id }})">
                            </i>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{-- Per Page --}}
        <div class="py-4 px-3">
            <div class="flex ">
                <div class="flex space-x-4 items-center mb-3">
                    <label class="w-32 text-sm font-medium text-gray-900">Per Page</label>
                    <select wire:model.live='perPage'
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                        <option value="5">5</option>
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
</div>