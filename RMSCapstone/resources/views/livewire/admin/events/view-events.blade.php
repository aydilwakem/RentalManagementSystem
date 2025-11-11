<div class="min-h-[550px] container mx-auto p-6 ">
    @if ($event->isEmpty() && !$search && !$transactionStatus)
    <!-- Empty Page Message -->
    <div class="text-center py-10">
        <p class="text-gray-500 text-lg font-semibold">No events yet.<br> Click "Create Event" to add a new event.
        </p>
        <x-button class="mt-4" href="{{ route('admin.create-event') }}" icon="fas fa-plus">
            Create Event
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
        <div class="flex items-center justify-between mb-4">
            <!-- Create Room Button -->
            @can('event-create')
            <div class="flex items-center justify-between">
                <x-button icon="fas fa-plus" href="{{ route('admin.create-event') }}">
                    New Event
                </x-button>
            </div>
            @endcan

                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="inline-flex items-center px-4 py-2 bg-gray-600 uppercase tracking-widest text-xs text-white rounded-md font-semibold hover:bg-gray-700">
                        More Actions <i class="fas fa-chevron-down ml-2"></i>
                    </button>

                    <div x-show="open" @click.away="open = false" x-transition
                        class="absolute right-0 mt-2 w-52 bg-white rounded-md shadow-lg border z-50 py-2">

                        <a href="{{ route('admin.events-archives') }}" wire:navigate
                            class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-archive mr-2"></i> View Archives
                        </a>

                        @can('event-soft-delete')
                            <a href="{{ route('admin.deleted-events') }}"
                                class="flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-100">
                                <i class="fas fa-trash mr-2"></i> Deleted Events
                            </a>
                        @endcan
                    </div>
                </div>
            </div>


        <!-- Table -->

            <div
                class="bg-white rounded-lg shadow-md overflow-x-auto border dark:bg-gray-800 dark:text-white dark:border-t dark:border-gray-700">
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
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2
                                            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                placeholder="Search" required="">
                        </div>
                    </div>
                </div>

                {{-- Event Status Sort --}}
                <div class="flex space-x-3">
                    <div class="flex space-x-3 items-center">
                        <label class="w-40 text-sm font-medium text-gray-900 dark:text-white">Event Status :</label>
                        <select wire:model.live="transactionStatus"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5
                                            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                            <option value="">All</option>
                            <option value="pending">Pending</option>
                            <option value="receipt_verified">Payment Verified</option>
                            <option value="reserved">Reserved</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="on-going">On-going</option>
                            <option value="done">Done</option>
                            <option value="terminated">Terminated</option>
                            <option value="expired">Expired</option>
                        </select>
                    </div>
                </div>
                <!-- Table Body -->
                <div wire:loading wire:target="search, transactionStatus"
                    class="w-full flex items-center justify-center min-h-[50px] relative mt-24">
                    <div class="flex flex-col items-center justify-center text-center">
                        <!-- Spinner -->
                        <svg class="animate-spin h-6 w-6 text-green-700 mb-2" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4" />
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z" />
                        </svg>
                        <span class="text-green-700 text-sm">Loading...</span>
                    </div>
                </div>
                <table class="w-full text-left">
                    <thead wire:loading.remove wire:target="search, transactionStatus"
                        class="text-sm text-gray-700 bg-gray-200 dark:bg-gray-800 dark:text-white dark:border-t dark:border-gray-700">
                        <tr>
                            {{-- Transaction Number --}}
                            <th scope="col" class="px-4 py-3">Event ID</th>
                            <th scope="col" class="px-4 py-3">Company Name</th>
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
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="size-4 ml-1">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                            </svg>
                                        @else
                                            {{-- Down arrow (Descending) --}}
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
                            <th scope="col" class="px-4 py-3">Event End</th>
                            <th scope="col" class="px-4 py-3 text-center">Status</th>
                            <th scope="col" class="px-4 py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody wire:loading.remove wire:target="search, transactionStatus" class="dark:bg-gray-700">
                        @forelse ($event as $eventItem)
                            <tr
                                class="border-b hover:bg-gray-50 dark:hover:bg-gray-600 dark:border-gray-700 odd:dark:bg-gray-700 even:dark:bg-gray-800">
                                <th scope="row"
                                    class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $eventItem->transaction_number }}
                                </th>

                                <td class="px-4 py-3">
                                    {{ $eventItem->transactionUser->company_name ?? 'N/A' }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $eventItem->transactionUser->first_name }}
                                    {{ $eventItem->transactionUser->last_name }}
                                </td>
                                <td class="px-4 py-3"> {{ $eventItem->event_type->name ?? 'N/A' }} </td>
                                <td class="px-4 py-3">
                                    @foreach ($eventItem->properties->where('property_type_id', 3) as $index => $property)
                                        {{ $property->name_number ?? 'N/A' }}{{ !$loop->last ? ', ' : '' }}
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
                                    @if ($eventItem->transaction_status === 'pending')
                                        <span
                                            class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-gray-100 text-gray-600 text-center">
                                            Awaiting Payment
                                        </span>
                                    @elseif ($eventItem->transaction_status === 'reserved')
                                        <span
                                            class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-blue-100 text-blue-500 text-center">
                                            Pending Verification
                                        </span>
                                    @elseif ($eventItem->transaction_status === 'receipt_verified')
                                        <span
                                            class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-cyan-100 text-cyan-500 text-center">
                                            Payment Verified
                                        </span>
                                    @elseif ($eventItem->transaction_status === 'confirmed')
                                        <span
                                            class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-emerald-100 text-emerald-600 text-center">
                                            Confirmed
                                        </span>
                                    @elseif ($eventItem->transaction_status === 'ongoing')
                                        <span
                                            class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-600 text-center">
                                            On-Going
                                        </span>
                                    @elseif ($eventItem->transaction_status === 'done')
                                        <span
                                            class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-indigo-200 text-indigo-600 text-center">
                                            Completed
                                        </span>
                                    @elseif ($eventItem->transaction_status === 'no_show')
                                        <span
                                            class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-pink-100 text-pink-500 text-center">
                                            No Show
                                        </span>
                                    @elseif ($eventItem->transaction_status === 'terminated')
                                        <span
                                            class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-rose-100 text-rose-600 text-center">
                                            Terminated
                                        </span>
                                    @elseif ($eventItem->transaction_status === 'expired')
                                        <span
                                            class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-orange-100 text-orange-500 text-center">
                                            Expired
                                        </span>
                                    @elseif ($eventItem->transaction_status === 'cancelled')
                                        <span
                                            class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-red-100 text-red-600 text-center">
                                            Cancelled
                                        </span>
                                    @elseif ($eventItem->transaction_status === 'archived')
                                        <span
                                            class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-gray-300 text-gray-700 text-center">
                                            Archived
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 flex items-center justify-center space-x-2">
                                    <!-- View Icon -->
                                    @can('event-view')
                                        <i class="fas fa-eye text-gray-700 hover:text-blue-600 cursor-pointer dark:text-gray-200 dark:hover:text-blue-500"
                                            wire:navigate
                                            href="{{ route('admin.view-event', ['event' => $eventItem->id]) }}">
                                        </i>
                                    @endcan

                                    <!-- Edit Icon -->
                                    @can('event-edit')
                                        <i class=" fas fa-edit text-gray-700 hover:text-yellow-600 cursor-pointer dark:text-gray-200 dark:hover:text-yellow-500"
                                            wire:navigate
                                            href="{{ route('admin.edit-event', ['event' => $eventItem->id]) }}">
                                        </i>
                                    @endcan

                                    <!-- Delete Icon -->
                                    @can('event-delete')
                                        <i class="fas fa-trash-alt text-gray-700 hover:text-red-600 cursor-pointer dark:text-gray-200 dark:hover:text-red-500"
                                            wire:click="confirmDelete({{ $eventItem->id }})"
                                            wire:loading.attr="disabled">
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
                    </tbody>
                </table>

                {{-- Pagination --}}
                <div class="py-4 px-3 dark:bg-gray-800 dark:text-white rounded-lg">
                    <div class="flex ">
                        <div class="flex space-x-4 items-center mb-3">
                            <label class="w-32 text-sm font-medium text-gray-900 dark:text-white">Per Page</label>
                            <select wire:model.live="perPage"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5
                                            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
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
                        <x-secondary-button wire:click="$set('confirmItemDelete', false)"
                            wire:loading.attr="disabled">
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
            <!-- Table Body -->
            <div wire:loading wire:target="search, transactionStatus"
                class="w-full flex items-center justify-center min-h-[50px] relative mt-24">
                <div class="flex flex-col items-center justify-center text-center">
                    <!-- Spinner -->
                    <svg class="animate-spin h-6 w-6 text-green-700 mb-2" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z" />
                    </svg>
                    <span class="text-green-700 text-sm">Loading...</span>
                </div>
            </div>
            <table class="w-full text-left">
                <thead wire:loading.remove wire:target="search, transactionStatus"
                    class="text-sm text-gray-700 bg-gray-200 dark:bg-gray-800 dark:text-white dark:border-t dark:border-gray-700">
                    <tr>
                        {{-- Transaction Number --}}
                        <th scope="col" class="px-4 py-3">Event ID</th>
                        <th scope="col" class="px-4 py-3">Company Name</th>
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
                <tbody wire:loading.remove wire:target="search, transactionStatus" class="dark:bg-gray-700">
                    @forelse ($event as $eventItem)
                    <tr
                        class="border-b hover:bg-gray-50 dark:hover:bg-gray-600 dark:border-gray-700 odd:dark:bg-gray-700 even:dark:bg-gray-800">
                        <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $eventItem->transaction_number }}
                        </th>

                        <td class="px-4 py-3">
                            {{ $eventItem->transactionUser->company_name ?? 'N/A' }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $eventItem->transactionUser->first_name }}
                            {{ $eventItem->transactionUser->last_name }}
                        </td>
                        <td class="px-4 py-3"> {{ $eventItem->event_type->name ?? 'N/A' }} </td>
                        <td class="px-4 py-3">
                            @foreach ($eventItem->properties->where('property_type_id', 3) as $property)
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
                                class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-purple-100 text-purple-600">Payment
                                Verified
                            </span>
                            @elseif($eventItem->transaction_status === 'reserved')
                            <span
                                class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-pink-100 text-pink-600">Reserved

                            </span>
                            @elseif($eventItem->transaction_status === 'pending')
                            <span
                                class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-gray-100 text-gray-600">Pencil
                                Booked
                            </span>
                            @elseif($eventItem->transaction_status === 'ongoing')
                            <span
                                class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-600">On-Going
                            </span>
                            @elseif($eventItem->transaction_status === 'done')
                            <span
                                class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-cyan-100 text-cyan-500">Completed
                            </span>
                            @elseif($eventItem->transaction_status === 'terminated')
                            <span
                                class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-rose-100 text-red-600">Terminated
                            </span>
                            @elseif($eventItem->transaction_status === 'expired')
                            <span
                                class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-orange-100 text-orange-600">Expired
                            </span>
                            @elseif($eventItem->transaction_status === 'cancelled')
                            <span
                                class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-rose-100 text-rose-600">Cancelled
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 flex items-center justify-center space-x-2">
                            <!-- View Icon -->
                            @can('event-view')
                            <i class="fas fa-eye text-gray-700 hover:text-blue-600 cursor-pointer dark:text-gray-200 dark:hover:text-blue-500"
                                wire:navigate href="{{ route('admin.view-event', ['event' => $eventItem->id]) }}">
                            </i>
                            @endcan

                            <!-- Edit Icon -->
                            @can('event-edit')
                            <i class=" fas fa-edit text-gray-700 hover:text-yellow-600 cursor-pointer dark:text-gray-200 dark:hover:text-yellow-500"
                                wire:navigate href="{{ route('admin.edit-event', ['event' => $eventItem->id]) }}">
                            </i>
                            @endcan

                            <!-- Delete Icon -->
                            @can('event-delete')
                            <i class="fas fa-trash-alt text-gray-700 hover:text-red-600 cursor-pointer dark:text-gray-200 dark:hover:text-red-500"
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
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="py-4 px-3 dark:bg-gray-800 dark:text-white rounded-lg">
                <div class="flex ">
                    <div class="flex space-x-4 items-center mb-3">
                        <label class="w-32 text-sm font-medium text-gray-900 dark:text-white">Per Page</label>
                        <select wire:model.live="perPage"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5
                                            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
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
