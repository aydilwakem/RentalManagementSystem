<div class="min-h-[550px] container mx-auto p-6 ">

    @if ($transactions->isEmpty())
        <!-- Navigation Tabs -->
        {{-- <ul class="flex flex-wrap text-sm font-medium text-center text-gray-600 border-gray-300">
            <li class="me-2">
                <a href="{{ route('admin.reservations-list') }}"
                    class="inline-block p-4 {{ Route::is('admin.reservations-list') ? 'text-green-700 bg-green-100 font-semibold rounded-t-lg' : 'hover:text-green-700 hover:bg-green-50 rounded-t-lg' }}">
                    New Reservations
                </a>
            </li>
            <li class="me-2">
                <a href="{{ route('admin.view-confirmed-transactions') }}"
                    class="inline-block p-4 {{ Route::is('admin.view-confirmed-transactions') ? 'text-green-700 bg-green-100 font-semibold rounded-t-lg' : 'hover:text-green-700 hover:bg-green-50 rounded-t-lg' }}">
                    Confirmed Reservations
                </a>
            </li>
            <li class="me-2">
                <a href="{{ route('admin.view-ongoing-transactions') }}"
                    class="inline-block p-4 {{ Route::is('admin.view-ongoing-transactions') ? 'text-green-700 bg-green-100 font-semibold rounded-t-lg' : 'hover:text-green-700 hover:bg-green-50 rounded-t-lg' }}">
                    On-Going Bookings
                </a>
            </li>
            <li class="me-2">
                <a href="{{ route('admin.view-old-transactions') }}"
                    class="inline-block p-4 {{ Route::is('admin.view-old-transactions') ? 'text-green-700 bg-green-100 font-semibold rounded-t-lg' : 'hover:text-green-700 hover:bg-green-50 rounded-t-lg' }}">
                    Old Bookings
                </a>
            </li>
        </ul> --}}

        <div class="bg-white rounded-lg shadow-md overflow-x-auto border">
            <!-- Empty Table Message -->
            <div class="text-center py-10">
                <p class="text-gray-500 text-lg font-semibold">No new reservations yet.<br> Click "Create Reservation"
                    to
                    add a reservation.</p>
                <x-button class="mt-4" href="{{ route('admin.create-new-transaction') }}" icon="fas fa-plus" wire:navigate>
                    Create Reservation
                </x-button>
            </div>
        </div>
    @else
        <div>

            {{-- Display Session Message --}}
            @if (session('message'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                    class="fixed top-4 left-1/2 transform -translate-x-1/2 px-4 py-2 rounded-lg shadow-lg
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        {{ session('alert-type') === 'success' ? 'bg-red-500 text-white' : 'bg-green-500 text-white' }}">
                    {{ session('message') }}
                </div>
            @endif

            <!-- Navigation Tabs -->
            {{-- <ul class="flex flex-wrap text-sm font-medium text-center text-gray-600 border-gray-300">

                <li class="me-2">
                    <a href="{{ route('admin.reservations-list') }}" wire:navigate
                        class="inline-block p-4 {{ Route::is('admin.reservations-list') ? 'text-green-700 bg-green-100 font-semibold rounded-t-lg' : 'hover:text-green-700 hover:bg-green-50 rounded-t-lg' }}">
                        New Reservations
                    </a>
                </li>

                @can('confirmed-reservation-list')
                <li class="me-2">
                    <a href="{{ route('admin.view-confirmed-transactions') }}" wire:navigate
                        class="inline-block p-4 {{ Route::is('admin.view-confirmed-transactions') ? 'text-green-700 bg-green-100 font-semibold rounded-t-lg' : 'hover:text-green-700 hover:bg-green-50 rounded-t-lg' }}">
                        Confirmed Reservations
                    </a>
                </li>
                @endcan

                @can('on-going-booking-list')
                <li class="me-2">
                    <a href="{{ route('admin.view-ongoing-transactions') }}" wire:navigate
                        class="inline-block p-4 {{ Route::is('admin.view-ongoing-transactions') ? 'text-green-700 bg-green-100 font-semibold rounded-t-lg' : 'hover:text-green-700 hover:bg-green-50 rounded-t-lg' }}">
                        On-Going Bookings
                    </a>
                </li>
                @endcan

                @can('old-booking-list')
                <li class="me-2">
                    <a href="{{ route('admin.view-old-transactions') }}" wire:navigate
                        class="inline-block p-4 {{ Route::is('admin.view-old-transactions') ? 'text-green-700 bg-green-100 font-semibold rounded-t-lg' : 'hover:text-green-700 hover:bg-green-50 rounded-t-lg' }}">
                        Old Bookings
                    </a>
                </li>
                @endcan

            </ul> --}}

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
                            <input wire:model.live.debounce.300ms="search" type="text"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 "
                                placeholder="Search" required="">
                        </div>
                    </div>
                    <!-- Create Room Button -->
                    <div class="flex space-x-4">

                        @can('new-reservation-create')
                            <x-button icon="fas fa-plus" href="{{ route('admin.create-new-transaction') }}">
                                New Transaction
                            </x-button>
                        @endcan

                        @can('new-reservation-soft-delete')
                            <x-button
                                class="!bg-gray-600 hover:!bg-gray-700 focus:ring focus:!ring-gray-600 focus:!ring-offset-2"
                                icon="fas fa-trash" href="{{ route('admin.deleted-new-transactions') }}">
                                Deleted New Reservations
                            </x-button>
                        @endcan
                    </div>

                    {{-- Status Type --}}
                    <div class="flex space-x-3">
                        <div class="flex space-x-3 items-center">
                            <label class="w-40 text-sm font-medium text-gray-900">Reservation Status:</label>
                            <select wire:model.live="statusFilter"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option value="">All</option>
                                <option value="pending">Pending</option>
                                <option value="reserved">Waiting for Confirmation</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="ongoing">On-going</option>
                                <option value="done">Done</option>
                                <option value="no_show">Out of Service</option>
                                <option value="terminated">Terminated</option>
                                <option value="expired">Expired</option>
                            </select>
                        </div>
                    </div>

                </div>

                {{-- Table --}}
                <table class="w-full text-left">
                    <thead class="text-sm text-gray-700 bg-gray-200">
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

                            <th scope="col" class="px-4 py-3" wire:click="setSortBy('first_name')">
                                <button class="flex items-center">
                                    Guest Name
                                    @if ($sortBy !== 'first_name')
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

                            {{-- Room Name --}}
                            <th scope="col" class="px-4 py-3" wire:click="setSortBy('room_id')">
                                <button class="flex items-center">
                                    Room Name
                                    @if ($sortBy !== 'room_id')
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

                            {{-- Pax --}}
                            <th scope="col" class="px-4 py-3" wire:click="setSortBy('pax')">
                                <button class="flex items-center">
                                    Pax
                                    @if ($sortBy !== 'pax')
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


                            <th scope="col" class="px-4 py-3">Duration</th>




                            <th scope="col" class="px-4 py-3" wire:click="setSortBy('start_datetime')">
                                <button class="flex items-center">
                                    Check-in
                                    @if ($sortBy !== 'start_datetime')
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

                            <th scope="col" class="px-4 py-3" wire:click="setSortBy('end_datetime')">
                                <button class="flex items-center">
                                    Check-out
                                    @if ($sortBy !== 'end_datetime')
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

                            <th scope="col" class="px-4 py-3">Status</th>
                            <th scope="col" class="px-4 py-3">Action</th>

                        </tr>
                    </thead>
                    <tbody class="text-left">
                        @foreach ($transactions as $transaction)
                                        <tr class="border-b">

                                            <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                                                {{ $fakeIDs[$transaction->id] ?? 'TXN-' . str_pad($loop->index + 1, 3, '0', STR_PAD_LEFT) }}
                                            </th>

                                            <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                                                {{ $transaction->transactionUser->first_name }}
                                                {{ $transaction->transactionUser->last_name }}
                                            </th>


                                            <td class="px-4 py-3">
                                                @foreach ($transaction->properties as $property)
                                                    {{ $property->name_number ?? 'N/A' }}<br>
                                                @endforeach
                                            </td>

                                            <td class="px-4 py-3"> {{ $transaction->pax }}</td>

                                            <td class="px-4 py-3">
                                                {{ optional($transaction->properties->first()->pivot)->days ?? 'N/A' }} day(s)
                                            </td>


                                            <td class="px-4 py-3">
                                                {{ \Carbon\Carbon::parse($transaction->start_datetime)->format('F j, Y') }}
                                            </td>

                                            <td class="px-4 py-3">
                                                {{ \Carbon\Carbon::parse($transaction->end_datetime)->format('F j, Y') }}
                                            </td>


                                            {{-- @can('new-reservation-confirm-receipt')
                                            <td class="px-4 py-3 text-center">
                                                <span
                                                    class="cursor-pointer font-semibold
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    {{ $transaction->isPaid ? 'text-green-600' : 'text-yellow-500' }}                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             hover:underline"
                                                    wire:click="confirmReceipt({{ $transaction->id }})" wire:loading.attr="disabled">
                                                    {{ $transaction->isPaid ? 'Confirmed' : 'Confirm Receipt' }}
                                                </span>
                                            </td>
                                            @endcan --}}

                                            {{-- Transaction Status --}}
                                            <td class="px-4 py-3">
                                                @if ($transaction->transaction_status === 'pending')
                                                    <span class="px-2 py-1 bg-gray-500 text-white rounded-md">Pending</span>
                                                @elseif ($transaction->transaction_status === 'reserved')
                                                    <span class="px-2 py-1 bg-blue-500 text-white rounded-md">Waiting for Confirmation</span>
                                                @elseif ($transaction->transaction_status === 'confirmed')
                                                    <span class="px-2 py-1 bg-green-500 text-white rounded-md">Confirmed</span>
                                                @elseif ($transaction->transaction_status === 'ongoing')
                                                    <span class="px-2 py-1 bg-yellow-500 text-white rounded-md">Ongoing</span>
                                                @elseif ($transaction->transaction_status === 'done')
                                                    <span class="px-2 py-1 bg-indigo-600 text-white rounded-md">Done</span>
                                                @elseif ($transaction->transaction_status === 'no_show')
                                                    <span class="px-2 py-1 bg-pink-500 text-white rounded-md">No Show</span>
                                                @elseif ($transaction->transaction_status === 'terminated')
                                                    <span class="px-2 py-1 bg-red-600 text-white rounded-md">Terminated</span>
                                                @elseif ($transaction->transaction_status === 'expired')
                                                    <span class="px-2 py-1 bg-red-600 text-white rounded-md">Expired</span>
                                                @endif
                                            </td>


                                            {{-- Action Icons --}}
                                            <td class="px-4 py-3 flex items-center space-x-3 relative">

                                                <!-- View Icon -->
                                                @can('new-reservation-view')
                                                    <i class="fas fa-eye text-gray-700 hover:text-blue-600 cursor-pointer" wire:navigate
                                                        href="{{ route('admin.view-new-transaction', ['transaction' => $transaction->id]) }}">
                                                    </i>
                                                @endcan


                                                @php
                                                    $dropdownId = 'dropdown-' . $transaction->id;
                                                    $buttonId = 'dropdownDefaultButton-' . $transaction->id;
                                                @endphp

                                                <button data-toggle="dropdown" data-id="{{ $transaction->id }}"
                                                    class="text-gray-700 hover:text-blue-600 focus:outline-none">
                                                    <i class="fas fa-ellipsis-v text-xl"></i>
                                                </button>

                                                <div data-dropdown="{{ $transaction->id }}"
                                                    class="dropdown-menu absolute top-full mt-2 right-0 z-10 hidden bg-white divide-y divide-gray-100                                                                                                                                                                                                                                                                                                                                                                                                        rounded-lg shadow-sm w-44 dark:bg-gray-700">
                                                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200">


                                                        <!-- Confirm Reservation -->
                                                        @if ($transaction->transaction_status === 'reserved')
                                                            <li>
                                                                <a href="#"
                                                                    class="flex items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                                    <i class="fas fa-check-circle mr-2 text-green-600"></i> Confirm Reservation
                                                                </a>
                                                            </li>
                                                        @endif

                                                        <!-- Confirm Receipt -->
                                                        @if ($transaction->transaction_status === 'reserved')
                                                            <li>
                                                                <button type="button"
                                                                    class="flex items-center w-full px-4 py-2 text-left hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                                                                    wire:click.prevent="confirmReceipt({{ $transaction->id }})"
                                                                    wire:loading.attr="disabled">
                                                                    <i class="fas fa-receipt mr-2 text-blue-600"></i> Confirm Receipt
                                                                </button>
                                                            </li>
                                                            </i>
                                                        @endif

                                                        <!-- Edit Transaction -->
                                                        @if ($transaction->transaction_status === 'pending' || $transaction->transaction_status === 'reserved')
                                                            <li>
                                                                <a href="{{ route('admin.edit-new-transaction', ['transaction' => $transaction->id]) }}"
                                                                    class="flex items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                                    <i class="fas fa-edit mr-2 text-yellow-500"></i> Edit Transaction
                                                                </a>
                                                            </li>
                                                        @endif

                                                        <!-- Start Reservation -->
                                                        @if ($transaction->transaction_status === 'confirmed')
                                                            <li>
                                                                <a href="#"
                                                                    class="flex items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                                    <i class="fas fa-play-circle mr-2 text-indigo-600"></i> Start Reservation
                                                                </a>
                                                            </li>
                                                        @endif

                                                        <!-- Add Transaction -->
                                                        @if ($transaction->transaction_status === 'ongoing')
                                                            <li>
                                                                <a href="#"
                                                                    class="flex items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                                    <i class="fas fa-plus-circle mr-2 text-green-500"></i> Add Transaction
                                                                </a>
                                                            </li>
                                                        @endif

                                                        <!-- Mark as Done -->
                                                        @if ($transaction->transaction_status === 'ongoing')
                                                            <li>
                                                                <a href="#"
                                                                    class="flex items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                                    <i class="fas fa-check-double mr-2 text-emerald-600"></i> Mark as Done
                                                                </a>
                                                            </li>
                                                        @endif

                                                        <!-- Mark as No Show -->
                                                        @if ($transaction->transaction_status === 'confirmed')
                                                            <li>
                                                                <a href="#"
                                                                    class="flex items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                                    <i class="fas fa-user-slash mr-2 text-pink-600"></i> Mark as No Show
                                                                </a>
                                                            </li>
                                                        @endif


                                                    </ul>

                                                    <!--------------------- Destructive Actions -------------------------->
                                                    <div class="py-2">

                                                        <!-- Cancel -->
                                                        @if ($transaction->transaction_status === 'pending' || $transaction->transaction_status === 'reserved')
                                                            <a href="#"
                                                                class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                                                <i class="fas fa-times mr-2"></i> Cancel
                                                            </a>
                                                        @endif

                                                        <!-- Terminate -->
                                                        @if ($transaction->transaction_status === 'ongoing')
                                                            <a href="#"
                                                                class="flex items-center px-4 py-2 text-sm text-yellow-600 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                                <i class="fas fa-ban mr-2"></i> Terminate
                                                            </a>
                                                        @endif

                                                        <!-- Delete -->
                                                        @if ($transaction->transaction_status === 'expired' || $transaction->transaction_status === 'done' || $transaction->transaction_status === 'no_show' || $transaction->transaction_status === 'terminated')
                                                            <a href="#" wire:click.prevent="confirmDelete({{ $transaction->id }})"
                                                                wire:loading.attr="disabled"
                                                                class="flex items-center px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                                <i class="fas fa-trash-alt mr-2"></i> Delete
                                                            </a>
                                                        @endif

                                                    </div>

                                                </div>


                                            </td>
                                        </tr>
                        @endforeach
                    </tbody>
                </table>


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
                    {{ $transactions->links() }}
                </div>

                <!-- Delete Confirmation Modal -->
                <x-dialog-modal wire:model.live="confirmItemDelete">
                    <x-slot name="title">
                        {{ __('Delete Transaction') }}
                    </x-slot>

                    <x-slot name="content">
                        {{ __('Are you sure you want to delete this item?') }}
                    </x-slot>

                    <x-slot name="footer">
                        <x-secondary-button wire:click="$set('confirmItemDelete', false)" wire:loading.attr="disabled">
                            {{ __('Cancel') }}
                        </x-secondary-button>

                        <x-danger-button class="ms-3" wire:click="deleteTransaction({{ $transaction->id }})"
                            wire:loading.attr="disabled">
                            {{ __('Delete Transaction') }}
                        </x-danger-button>
                    </x-slot>
                </x-dialog-modal>

                <!-- Receipt Confirmation Modal -->
                <x-dialog-modal wire:model.live="confirmItemReceipt">
                    <x-slot name="title">
                        {{ __('Confirm Receipt') }}
                    </x-slot>

                    <x-slot name="content">
                        @if ($selectedTransaction)
                            <!-- Payment Screenshot at the Top -->
                            <div class="flex flex-col items-center">
                                <img src="{{ asset($selectedTransaction->payment_screenshot ? 'storage/' . $selectedTransaction->payment_screenshot : 'images/rms-default.png') }}"
                                    alt="Payment Screenshot" class="w-64 h-auto mb-4">
                            </div>

                            <!-- Payment Details Below -->
                            <div class="text-left">
                                <p class="text-lg font-semibold">Name: {{ $selectedTransaction->first_name ?? 'N/A' }}
                                </p>
                                <p class="text-lg font-semibold">Payment Method:
                                    {{ $selectedTransaction->paymentMethod->mode_of_payment_name ?? 'N/A' }}
                                </p>
                                <p class="text-lg font-semibold">Payment Reference:
                                    {{ $selectedTransaction->payment_reference_number ?? 'N/A' }}
                                </p>
                            </div>
                        @else
                            {{ __('No payment screenshot available.') }}
                        @endif
                    </x-slot>
                    <p></p>

                    <x-slot name="footer">
                        <x-secondary-button wire:click="$set('confirmItemReceipt', false)" wire:loading.attr="disabled">
                            {{ __('Cancel') }}
                        </x-secondary-button>

                        <x-button class="ms-3" wire:click="confirmPaymentReceipt({{ $transaction?->id }})"
                            wire:loading.attr="disabled">
                            {{ __('Confirm Receipt') }}
                        </x-button>
                    </x-slot>
                </x-dialog-modal>

            </div>
        </div>
    @endif
</div>



<script>
    document.querySelectorAll('[data-toggle="dropdown"]').forEach(button => {
        button.addEventListener("click", function (e) {
            e.stopPropagation(); // Prevent window click from firing
            const id = button.getAttribute("data-id");
            const dropdown = document.querySelector(`[data-dropdown="${id}"]`);
            dropdown.classList.toggle("hidden");

            // Optional: Hide others
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                if (menu !== dropdown) {
                    menu.classList.add("hidden");
                }
            });
        });
    });

    // Optional: Close all dropdowns when clicking outside
    window.addEventListener("click", function () {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.classList.add("hidden");
        });
    });
</script>

<!-- Confirm Reservation Icon -->
{{-- @can('new-reservation-confirm')
<i class="fa-solid fa-circle-check
                                            {{ $transaction->isPaid ? 'text-green-600 cursor-pointer hover:text-green-700' : 'text-gray-400 cursor-not-allowed' }}"
    @if (!$transaction->isPaid) disabled @endif wire:click.prevent="{{ $transaction->isPaid
    ? "confirmReservation($transaction->id)" :
    '' }}" wire:loading.attr="disabled">
</i>
@endcan --}}

{{-- <li>
    @can('new-reservation-confirm')
    <a href="#"
        class="flex items-center space-x-2 px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white 
                                                        {{ $transaction->transaction_status == 'reserved' ? '' : 'cursor-not-allowed text-gray-400 pointer-events-none' }}"
        @if($transaction->transaction_status == 'reserved')
        wire:click.prevent="confirmReservation({{ $transaction->id }})"
        wire:loading.attr="disabled"
        @endif>
        <i
            class="fa-solid fa-circle-check 
                                                            {{ $transaction->transaction_status == 'reserved' ? 'text-green-600 hover:text-green-700' : 'text-gray-400' }}">
        </i>
        <span>Confirm Receipt</span>
    </a>
    @endcan
</li> --}}