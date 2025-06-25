<div class="min-h-[550px] container mx-auto p-6 ">
    @if ($allEvents->isEmpty())
    <!-- Empty Page Message -->
    <div class="text-center py-10">
        <p class="text-gray-500 text-lg font-semibold">No events yet.<br> Click "Create Event" to add a new event.
        </p>
        <x-button class="mt-4" href="{{ route('admin.create-event') }}" icon="fas fa-plus">
            Create Event
        </x-button>
    </div>
    @else
    <div>
        <div class="flex items-center justify-between mb-4">
            <!-- Create Room Button -->
            @can('event-create')
            <div class="flex items-center justify-between">
                <x-button icon="fas fa-plus" href="{{ route('admin.create-event') }}">
                    New Event
                </x-button>
            </div>
            @endcan
            @can('event-soft-delete')
            <!-- Deleted Items (Restore and Delete Forever) -->
            <x-button class="!bg-gray-600 hover:!bg-gray-700 focus:ring focus:!ring-gray-600 focus:!ring-offset-2"
                icon="fas fa-trash" href="{{ route('admin.deleted-events') }}">
                Deleted Events
            </x-button>
            @endcan
        </div>

        {{-- Display Session Message --}}
        @if (session('message'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" class="fixed top-4 left-1/2 transform -translate-x-1/2 px-4 py-2 rounded-lg shadow-lg
                    {{ session('alert-type') === 'success' ? 'bg-red-500 text-white' : 'bg-green-500 text-white' }}">
            {{ session('message') }}
        </div>
        @endif
        <!-- Table -->
        <div class="bg-white rounded-lg shadow-md overflow-x-auto border">
            <!-- Header-->
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
                        {{-- Search Bar --}}
                        <input wire:model.live.debounce.300ms="search" type="text"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 "
                            placeholder="Search" required="">
                    </div>
                </div>

                {{-- Event Status Sort --}}
                <div class="flex space-x-3">
                    <div class="flex space-x-3 items-center">
                        <label class="w-40 text-sm font-medium text-gray-900">Event Status :</label>
                        <select wire:model.live="transactionStatus"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                            <option value="">All</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="on-going">On-going</option>
                            <option value="done">Done</option>
                            <option value="terminated">Terminated</option>
                        </select>
                    </div>
                </div>
            </div>
            <!-- Table Body -->
            <table class="w-full text-left">
                <thead class="text-sm text-gray-700 bg-gray-200">
                    <tr>

                        {{-- Transaction Number --}}
                        <th scope="col" class="px-4 py-3">Event ID</th>



                        <th scope="col" class="px-4 py-3" wire:click="setSortBy('name')">
                            <button class="flex items-center">
                                Booked By
                                @if ($sortBy !== 'name')
                                {{-- Default icon when sorting is not active --}}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                                @else
                                @if ($sortDir == 'ASC')
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
                        <th scope="col" class="px-4 py-3">Company</th>
                        <th scope="col" class="px-4 py-3">Event Type</th>
                        <th scope="col" class="px-4 py-3">Event Hall</th>
                        <th scope="col" class="px-4 py-3" wire:click="setSortBy('event_date_start')">
                            <button class="flex items-center">
                                Event Start
                                @if ($sortBy !== 'event_date_start')
                                {{-- Default icon when sorting is not active --}}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                                @else
                                @if ($sortDir == 'ASC')
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
                        <th scope="col" class="px-4 py-3">Status</th>
                        <th scope="col" class="px-4 py-3 text-center">Action</th>
                    </tr>
                </thead>

                @forelse ($event as $eventItem)
                <tr class="border-b hover:bg-gray-50">
                    <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                        {{ $eventItem->transaction_number }}
                    </th>
                    <td class="px-4 py-3">
                        {{ $eventItem->transactionUser->first_name }}
                        {{ $eventItem->transactionUser->last_name }}
                    </td>
                    <td class="px-4 py-3"> {{ $eventItem->transactionUser->company_name }} </td>
                    <td class="px-4 py-3"> {{ $eventItem->event_type->name ?? 'N/A' }} </td>
                    <td class="px-4 py-3">
                        @foreach ($eventItem->properties as $property)
                        {{ $property->name_number ?? 'N/A' }}<br>
                        @endforeach
                    </td>
                    <td class="px-4 py-3">
                        {{ \Carbon\Carbon::parse($eventItem->start_datetime)->format('F j, Y') }}<br>
                        {{ \Carbon\Carbon::parse($eventItem->start_datetime)->format('g:i A') }}
                    </td>
                    <td class="px-4 py-3">
                        {{ \Carbon\Carbon::parse($eventItem->end_datetime)->format('F j, Y') }}<br>
                        {{ \Carbon\Carbon::parse($eventItem->end_datetime)->format('g:i A') }}
                    </td>
                    <td class="px-4 py-3">
                        @if ($eventItem->transaction_status === 'confirmed')
                        <span
                            class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-emerald-100 text-emerald-600">
                            Confirmed
                        </span>
                        @elseif($eventItem->transaction_status === 'receipt_verified')
                        <span
                            class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-purple-100 text-purple-600">Reserved
                        </span>
                        @elseif($eventItem->transaction_status === 'ongoing')
                        <span
                            class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-600">On-Going
                        </span>
                        @elseif($eventItem->transaction_status === 'done')
                        <span
                            class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-cyan-100 text-cyan-500">Done
                        </span>
                        @elseif($eventItem->transaction_status === 'terminated')
                        <span
                            class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-rose-100 text-rose-600">Terminated
                        </span>
                        @endif
                    </td>
                    <td class="px-4 py-3 flex items-center justify-center space-x-2">
                        <!-- View Icon -->
                        @can('event-view')
                        <i class="fas fa-eye text-gray-700 hover:text-blue-600 cursor-pointer" wire:navigate
                            href="{{ route('admin.view-event', ['event' => $eventItem->id]) }}">
                        </i>
                        @endcan

                        <!-- Edit Icon -->
                        @can('event-edit')
                        <i class=" fas fa-edit text-gray-700 hover:text-yellow-600 cursor-pointer" wire:navigate
                            href="{{ route('admin.edit-event', ['event' => $eventItem->id]) }}">
                        </i>
                        @endcan

                        <!-- Delete Icon -->
                        @can('event-delete')
                        <i class="fas fa-trash-alt text-gray-700 hover:text-red-600 cursor-pointer"
                            wire:click="confirmDelete({{ $eventItem->id }})" wire:loading.attr="disabled">
                        </i>
                        @endcan

                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="15" class="text-center py-10 text-gray-500">
                        No events found.
                    </td>
                </tr>
                @endforelse

            </table>

            {{-- Pagination --}}
            <div class="py-4 px-3">
                <div class="flex ">
                    <div class="flex space-x-4 items-center mb-3">
                        <label class="w-32 text-sm font-medium text-gray-900">Per Page</label>
                        <select wire:model.live="perPage"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>
                {{ $event->links() }}
            </div>

            <!-- Delete Confirmation Modal -->
            <x-dialog-modal wire:model.live="confirmItemDelete" type="danger">
                <x-slot name="title">
                    {{ __('Delete Event') }}
                </x-slot>

                <x-slot name="content">
                    {{ __('Are you sure you want to delete this item?') }}
                </x-slot>

                <x-slot name="footer">
                    <x-secondary-button wire:click="$set('confirmItemDelete', false)" wire:loading.attr="disabled">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-danger-button class="ms-3" wire:click="deleteEvent" wire:loading.attr="disabled">
                        {{ __('Delete Event') }}
                    </x-danger-button>
                </x-slot>
            </x-dialog-modal>

            {{-- Cannot Delete Modal --}}
            <x-dialog-modal wire:model="cannotDeleteItem" type="ghost">
                <x-slot name="title">
                    {{ __('Unable to Delete') }}
                </x-slot>

                <x-slot name="content">
                    {{ __('This event is confirmed or on-going and cannot be deleted.') }}
                </x-slot>

                <x-slot name="footer">
                    <x-secondary-button wire:click="$set('cannotDeleteItem', false)" wire:loading.attr="disabled">
                        {{ __('OK') }}
                    </x-secondary-button>
                </x-slot>
            </x-dialog-modal>

        </div>

    </div>
    @endif
</div>
