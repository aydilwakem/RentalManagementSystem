<div class="min-h-[550px] container mx-auto p-6 ">
    @if ($roomRates->isEmpty())
        <!-- Empty Table Message -->
        <div class="text-center py-10">
            <p class="text-gray-500 text-lg font-semibold">No rooms rates yet.<br> Click "Create Room Rate" to add a new
                room rate.</p>
            <x-button class="mt-4" href="{{ route('admin.create-room-rate') }}" icon="fas fa-plus" wire:navigate>
                Create Room Rate
            </x-button>
        </div>
    @else
        <div>
            <!-- Create Room Rate Button -->
            @can('room-rate-create')
            <div class="flex items-center justify-between p-4">
                <x-button icon="fas fa-plus" href="{{ route('admin.create-room-rate') }}">
                    New Room Rate
                </x-button>
            </div>
            @endcan

            <div class="bg-white p-4 rounded-lg shadow-md overflow-x-auto border">
                <!-- Header-->
                <div class="flex items-center justify-between d p-4">
                    <!-- Search Tab -->
                    <div class="flex">
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg aria-hidden="true" class="w-5 h-5 text-gray-500 " fill="currentColor"
                                    viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input wire:model.live.debounce.300ms="search" type="text"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 "
                                placeholder="Search" required="">
                        </div>
                    </div>
                    <!-- Filter Tab -->
                    <div class="flex space-x-3">
                        <div class="flex space-x-3 items-center">
                            <label class="w-40 text-sm font-medium text-gray-900">Rate Type :</label>
                            <select wire:model.live="statusFilter"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option value="">All</option>
                                <option value="Weekend">Weekend</option>
                                <option value="Weekdays">Weekdays</option>
                            </select>
                        </div>
                    </div>
                </div>
                <table class="w-full text-left">
                    <thead class="text-sm text-gray-700 bg-gray-200">
                        {{-- ID --}}
                        <tr>
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

                            {{-- Room ID --}}
                            <th scope="col" class="px-4 py-3" wire:click="setSortBy('room_id')">
                                <button class="flex items-center">
                                    Room Name
                                    @if ($sortBy !== 'room_id')
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

                            {{-- Room Rate Name --}}
                            <th scope="col" class="px-4 py-3" wire:click="setSortBy('name')">
                                <button class="flex items-center">
                                    Name
                                    @if ($sortBy !== 'name')
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    @else
                                        @if ($sortDir == 'ASC')
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="size-4 ml-1">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="size-4 ml-1">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        @endif
                                    @endif
                                </button>
                            </th>

                            {{-- Start Date --}}
                            <th scope="col" class="px-4 py-3" wire:click="setSortBy('start_date')">
                                <button class="flex items-center">
                                    Start Date
                                    @if ($sortBy !== 'start_date')
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    @else
                                        @if ($sortDir == 'ASC')
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="size-4 ml-1">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="size-4 ml-1">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        @endif
                                    @endif
                                </button>
                            </th>

                            {{-- End Date --}}
                            <th scope="col" class="px-4 py-3" wire:click="setSortBy('end_date')">
                                <button class="flex items-center">
                                    End Date
                                    @if ($sortBy !== 'end_date')
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    @else
                                        @if ($sortDir == 'ASC')
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="size-4 ml-1">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="size-4 ml-1">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        @endif
                                    @endif
                                </button>
                            </th>

                            {{-- Amount --}}
                            <th scope="col" class="px-4 py-3" wire:click="setSortBy('amount')">
                                <button class="flex items-center">
                                    Amount
                                    @if ($sortBy !== 'amount')
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    @else
                                        @if ($sortDir == 'ASC')
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="size-4 ml-1">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="size-4 ml-1">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        @endif
                                    @endif
                                </button>
                            </th>

                            {{-- Extra Person Charge --}}
                            <th scope="col" class="px-4 py-3" wire:click="setSortBy('extra_person_charge')">
                                <button class="flex items-center">
                                    Extra Person Charge
                                    @if ($sortBy !== 'extra_person_charge')
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    @else
                                        @if ($sortDir == 'ASC')
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="size-4 ml-1">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="size-4 ml-1">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        @endif
                                    @endif
                                </button>
                            </th>

                            {{-- Extended Stay Charge Per Hr --}}
                            <th scope="col" class="px-4 py-3"
                                wire:click="setSortBy('extended_stay_charge_per_hr')">
                                <button class="flex items-center">
                                    Extended Stay Charage Per Hr
                                    @if ($sortBy !== 'extended_stay_charge_per_hr')
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    @else
                                        @if ($sortDir == 'ASC')
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="size-4 ml-1">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="size-4 ml-1">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        @endif
                                    @endif
                                </button>
                            </th>

                            {{-- Description --}}
                            <th scope="col" class="px-4 py-3" wire:click="setSortBy('description')">
                                <button class="flex items-center">
                                    Description
                                    @if ($sortBy !== 'description')
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    @else
                                        @if ($sortDir == 'ASC')
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="size-4 ml-1">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="size-4 ml-1">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        @endif
                                    @endif
                                </button>
                            </th>

                            {{-- Rate Type --}}
                            <th scope="col" class="px-4 py-3" wire:click="setSortBy('rate_type')">
                                <button class="flex items-center">
                                    Rate Type
                                    @if ($sortBy !== 'rate_type')
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    @else
                                        @if ($sortDir == 'ASC')
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="size-4 ml-1">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="size-4 ml-1">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        @endif
                                    @endif
                                </button>
                            </th>
                            <th scope="col" class="px-4 py-3 text-center">Actions</th>
                        </tr>
                    </thead>


                    @foreach ($roomRates as $roomRate)
                        <tr class="border-b">
                            {{-- Room Rate ID --}}
                            <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                                {{ $roomRate->id }}
                            </th>
                            {{-- Room ID --}}
                            <td class="px-4 py-3">{{ $roomRate->room->name ?? 'N/A' }}</td>
                            {{-- Room Rate Name --}}
                            <td class="px-4 py-3">{{ $roomRate->name }}</td>
                            {{-- Start Date --}}
                            <td class="px-4 py-3">{{ $roomRate->start_date }}</td>
                            {{-- End Date --}}
                            <td class="px-4 py-3">{{ $roomRate->end_date }}</td>
                            {{-- Amount --}}
                            <td class="px-4 py-3">{{ $roomRate->amount }}</td>
                            {{-- Extra Person Charge --}}
                            <td class="px-4 py-3">{{ $roomRate->extra_person_charge }}</td>
                            {{-- Extended Stay Charge Per Hr --}}
                            <td class="px-4 py-3">{{ $roomRate->extended_stay_charge_per_hr }}</td>
                            {{-- Description --}}
                            <td class="px-4 py-3">{{ $roomRate->description }}</td>
                            {{-- Rate Type --}}
                            <td class="px-4 py-3">
                                @if ($roomRate->rate_type === 'Weekdays')
                                    <span class="px-2 py-1 bg-green-700 text-white rounded">Weekdays</span>
                                @elseif($roomRate->rate_type === 'Weekend')
                                    <span class="px-2 py-1 bg-yellow-700 text-white rounded">Weekend</span>
                                @endif
                            </td>

                            <td class="px-4 py-3 flex items-center justify-center space-x-3">
                                <!-- View Icon -->
                                @can('room-rate-view')
                                <i class="fas fa-eye text-gray-700 hover:text-blue-600 cursor-pointer" wire:navigate
                                    href="{{ route('admin.view-room-rate', ['roomRate' => $roomRate->id]) }}">
                                </i>
                                @endcan

                                <!-- Edit Icon -->
                                @can('room-rate-edit')
                                <i class="fas fa-edit text-gray-700 hover:text-yellow-600 cursor-pointer" wire:navigate
                                    href="{{ route('admin.edit-room-rate', ['roomRate' => $roomRate->id]) }}">
                                </i>
                                @endcan

                                <!-- Delete Icon -->
                                @can('room-rate-delete')
                                <i class="fas fa-trash text-gray-700 hover:text-red-600 cursor-pointer"
                                    wire:click="deleteRoomRate({{ $roomRate->id }})">
                                </i>
                                @endcan

                            </td>
                        </tr>
                    @endforeach
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
                    {{ $roomRates->links() }}
                </div>
            </div>
        </div>

    @endif


</div>
