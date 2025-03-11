<div>
    <section class="mt-10">
        <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
            <!-- Start coding here -->
            <div class=" bg-white-500 relative shadow-md sm:rounded-lg overflow-hidden">

                <!-- Create Room Button -->
                <div class="flex items-center justify-between p-4">
                    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-blue-700 transition"
                        onclick="window.location.href='{{ route('admin.create-event') }}'">
                        + Create Event
                    </button>
                </div>

                {{-- Search Bar --}}
                <div class="flex items-center justify-between d p-4">
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

                    {{-- Event Status Sort --}}
                    <div class="flex space-x-3">
                        <div class="flex space-x-3 items-center">
                            <label class="w-40 text-sm font-medium text-gray-900">Event Status :</label>
                            <select wire:model.live="eventStatus"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                                <option value="">All</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="on-going">On-going</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 py-3" wire:click="setSortBy('id')">
                                    <button class="flex items-center">
                                        ID
                                        @if ($sortBy !== 'id')
                                        {{-- Default icon when sorting is not active --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                        @else
                                        @if($sortDir == 'ASC')
                                        {{-- Up arrow (Ascending) --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                        </svg>
                                        @else
                                        {{-- Down arrow (Descending) --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                        </svg>
                                        @endif
                                        @endif
                                    </button>
                                </th>
                                <th scope="col" class="px-4 py-3" wire:click="setSortBy('name')">
                                    <button class="flex items-center">
                                        NAME
                                        @if ($sortBy !== 'name')
                                        {{-- Default icon when sorting is not active --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                        @else
                                        @if($sortDir == 'ASC')
                                        {{-- Up arrow (Ascending) --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                        </svg>
                                        @else
                                        {{-- Down arrow (Descending) --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                        </svg>
                                        @endif
                                        @endif
                                    </button>
                                </th>
                                <th scope="col" class="px-4 py-3">Category</th>
                                <th scope="col" class="px-4 py-3">Event Hall</th>
                                <th scope="col" class="px-4 py-3" wire:click="setSortBy('company_name')">
                                    <button class="flex items-center">
                                        COMPANY NAME
                                        @if ($sortBy !== 'company_name')
                                        {{-- Default icon when sorting is not active --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                        @else
                                        @if($sortDir == 'ASC')
                                        {{-- Up arrow (Ascending) --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                        </svg>
                                        @else
                                        {{-- Down arrow (Descending) --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                        </svg>
                                        @endif
                                        @endif
                                    </button>
                                </th>
                                <th scope="col" class="px-4 py-3" wire:click="setSortBy('contact_person')">
                                    <button class="flex items-center">
                                        CONTACT PERSON
                                        @if ($sortBy !== 'contact_person')
                                        {{-- Default icon when sorting is not active --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                        @else
                                        @if($sortDir == 'ASC')
                                        {{-- Up arrow (Ascending) --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                        </svg>
                                        @else
                                        {{-- Down arrow (Descending) --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                        </svg>
                                        @endif
                                        @endif
                                    </button>
                                </th>
                                <th scope="col" class="px-4 py-3" wire:click="setSortBy('event_date_start')">
                                    <button class="flex items-center">
                                        EVENT START
                                        @if ($sortBy !== 'event_date_start')
                                        {{-- Default icon when sorting is not active --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                        @else
                                        @if($sortDir == 'ASC')
                                        {{-- Up arrow (Ascending) --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                        </svg>
                                        @else
                                        {{-- Down arrow (Descending) --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                        </svg>
                                        @endif
                                        @endif
                                    </button>
                                </th>
                                <th scope="col" class="px-4 py-3">Event End</th>
                                <th scope="col" class="px-4 py-3">Event Time</th>
                                <th scope="col" class="px-4 py-3">Capacity</th>
                                <th scope="col" class="px-4 py-3">Total Amount</th>
                                <th scope="col" class="px-4 py-3">Status</th>
                                <th scope="col" class="px-4 py-3 text-center">Action</th>
                                {{--<th scope="col" class="px-4 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>--}}
                            </tr>
                        </thead>

                        @foreach($event as $eventItem)
                        <tr class="border-b">
                            <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                                {{ $eventItem->id }}
                            </th>
                            <td class="px-4 py-3"> {{ $eventItem->name }} </td>
                            <td class="px-4 py-3"> {{ $eventItem->category->name }} </td>
                            <td class="px-4 py-3"> {{ $eventItem->eventHall->name }} </td>
                            <td class="px-4 py-3"> {{ $eventItem->company_name }} </td>
                            <td class="px-4 py-3"> {{ $eventItem->contact_person }} </td>
                            <td class="px-4 py-3"> {{ $eventItem->event_date_start }} </td>
                            <td class="px-4 py-3"> {{ $eventItem->event_date_end }} </td>
                            <td class="px-4 py-3"> {{ $eventItem->event_time }} </td>
                            <td class="px-4 py-3"> {{ $eventItem->capacity }} </td>
                            <td class="px-4 py-3"> {{ $eventItem->total_amount }} </td>
                            <td class="px-4 py-3">
                                @if($eventItem->status === 'confirmed')
                                <span class="px-2 py-1 bg-green-500 text-white rounded">Confirmed</span>
                                @elseif($eventItem->status === 'on-going')
                                <span class="px-2 py-1 bg-blue-500 text-white rounded">On-going</span>
                                @elseif($eventItem->status === 'cancelled')
                                <span class="px-2 py-1 bg-red-500 text-white rounded">Cancelled</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 flex items-center justify-center space-x-4">
                                <!-- View Icon -->
                                <i class="fas fa-eye text-blue-500 cursor-pointer">
                                </i>

                                <!-- Edit Icon -->
                                <i class=" fas fa-edit text-blue-500 cursor-pointer" wire:navigate
                                    href="{{ route('admin.edit-event', ['event' => $eventItem->id]) }}">
                                </i>
                                <!-- Delete Icon -->
                                <i class="fas fa-trash-alt text-red-500 cursor-pointer"
                                    wire:click="deleteEvent({{ $eventItem->id }})">
                                </i>

                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="py-4 px-3">
                    <div class="flex ">
                        <div class="flex space-x-4 items-center mb-3">
                            <label class="w-32 text-sm font-medium text-gray-900">Per Page</label>
                            <select wire:model.live="perPage"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>
                    {{ $event->links() }}
                </div>
            </div>
        </div>
    </section>

</div>