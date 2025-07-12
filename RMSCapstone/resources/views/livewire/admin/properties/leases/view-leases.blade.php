<div class="min-h-[550px] container mx-auto p-6 max-w-full">
    @if ($transactions->isEmpty() && !$statusFilter && !$search)
        <!-- Empty Page Message -->
        <div class="text-center py-10">
            <p class="text-gray-500 text-lg font-semibold">No leases yet.<br> Click "Create Lease" to add a new lease.
            </p>

            @can('leases-create')
                <x-button class="mt-4" href="{{ route('admin.create-lease') }}" icon="fas fa-plus">
                    Create Lease
                </x-button>
            @endcan
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

                @can('leases-create')
                    <!-- Create Button -->
                    <x-button icon="fas fa-plus" onclick="window.location.href='{{ route('admin.create-lease') }}'">
                        New Lease
                    </x-button>
                @endcan

                @can('leases-soft-delete')
                    <!-- Soft Deletes -->
                    <x-button
                        class="  !bg-gray-600 hover:!bg-gray-700 focus:ring focus:!ring-gray-600 focus:!ring-offset-2"
                        icon="fas fa-trash" href="{{ route('admin.deleted-leases') }}">
                        Deleted Leases
                    </x-button>
                @endcan

            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg shadow-md overflow-x-auto border dark:bg-gray-800 dark:border-gray-700 dark:text-white">
            <!-- Header-->
            <div class="flex items-center justify-between p-4 dark:bg-gray-800 rounded-lg">
                {{-- Search Tab --}}
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
                        <!-- Search-->
                        <input wire:model.live.debounce.300ms="search" type="text"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full pl-10 p-2
                            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            placeholder="Search">
                    </div>

                    {{-- Bulk Actions Button
                <div class="relative inline-block text-left ml-2" x-data="{ open: false }">
                    <button @click="open = !open" type="button"
                        class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Actions
                        <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="open" @click.away="open = false"
                        class="origin-top-right absolute right-0 mt-2 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                        <div class="py-1">
                            <a wire:click.prevent="confirmDeleteInBulk" href="#"
                                class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Bulk
                                Delete</a>
                        </div>
                    </div>
                </div> --}}
                </div>

                <!-- Status-->
                <div class="flex space-x-3">
                    <div class="flex space-x-3 items-center">
                        <label class="w-40 text-sm font-medium text-gray-900 dark:text-white">Lease Status:</label>
                        <select wire:model.live="statusFilter"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5
                            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                            <option value="">All</option>
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="ongoing">On-Going</option>
                            <option value="terminated">Terminated</option>
                        </select>
                    </div>
                </div>

            </div>

            <!-- Table Body-->
            <div class="overflow-x-auto">
                <table class="min-w-full text-left">
                    <thead class="text-sm text-gray-700 bg-gray-200 dark:bg-gray-800 dark:text-white dark:border-t dark:border-gray-700">
                        <tr>
                            <!-- Select All Checkbox-->
                            {{-- <th scope="col" class="px-4 py-3">
                            <input wire:model.live="selectPageRows" type="checkbox" id="checkAll"
                                class="accent-blue-600 w-4 h-4">
                        </th> --}}

                            {{-- Transaction Number --}}
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


                            <!-- Property -->
                            <th scope="col" class="px-4 py-3">
                                Property
                            </th>

                            <th scope="col" class="px-4 py-3">
                                Tenant
                            </th>

                            <!-- Monthly Rent-->
                            <th scope="col" class="px-4 py-3" wire:click="setSortBy('monthly_rent')">
                                <button class="flex items-center">
                                    Monthly Rent
                                    @if ($sortBy !== 'monthly_rent')
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

                            <!-- Start Date -->
                            <th scope="col" class="px-4 py-3" wire:click="setSortBy('start_datetime')">
                                <button class="flex items-center">
                                    Start Lease Date
                                    @if ($sortBy !== 'start_datetime')
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

                            <!-- End Date -->
                            <th scope="col" class="px-4 py-3" wire:click="setSortBy('end_datetime')">
                                <button class="flex items-center">
                                    End Lease Date
                                    @if ($sortBy !== 'end_datetime')
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

                            <!-- Status-->
                            <th scope="col" class="px-4 py-3">Status</th>

                            <!-- Actions -->
                            <th scope="col" class="px-4 py-3 text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="dark:bg-gray-700">
                        @forelse ($transactions as $transaction)
                            <tr class="border-b hover:bg-gray-50 dark:hover:bg-gray-600 dark:border-gray-700 odd:dark:bg-gray-700 even:dark:bg-gray-800">
                                {{-- <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                            <input wire:model.live="selectedRows" type="checkbox" name="transaction[]"
                                value="{{ $transaction->id }}" class="accent-blue-600 w-4 h-4">
                        </th> --}}
                                <th scope="row" class="font-medium text-gray-900 px-4 py-3 dark:text-white">
                                    <span>{{ $fakeIDs[$transaction->id] ?? 'LSE-???' }}</span>
                                </th>
                                <td class="px-4 py-3">
                                    @foreach ($transaction->properties as $property)
                                        {{ $property->name_number ?? 'N/A' }}<br>
                                    @endforeach
                                </td>
                                <td class="px-4 py-3">
                                    {{ $transaction->transactionUser->first_name }}
                                    {{ $transaction->transactionUser->last_name }}
                                </td>
                                <td class="px-4 py-3">
                                    @foreach ($transaction->properties as $property)
                                        ₱{{ number_format($property->amount, 2) }}<br>
                                    @endforeach
                                </td>
                                <td class="px-4 py-3">{{ $transaction->start_datetime->format('F j, Y') }}</td>
                                <td class="px-4 py-3">{{ $transaction->end_datetime->format('F j, Y') }}</td>
                                <td class="px-4 py-3">
                                    @if ($transaction->transaction_status === 'confirmed')
                                        <span
                                            class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-emerald-100 text-emerald-600">Confirmed
                                        </span>
                                    @elseif($transaction->transaction_status === 'pending')
                                        <span
                                            class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-gray-100 text-gray-600">Pending
                                        </span>
                                    @elseif($transaction->transaction_status === 'receipt_verified')
                                        <span
                                            class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-purple-100 text-purple-600">Reserved
                                        </span>
                                    @elseif($transaction->transaction_status === 'ongoing')
                                        <span
                                            class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-600">On-Going
                                        </span>
                                    @elseif($transaction->transaction_status === 'done')
                                        <span
                                            class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-cyan-100 text-cyan-500">Done
                                        </span>
                                    @elseif($transaction->transaction_status === 'terminated')
                                        <span
                                            class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-rose-100 text-rose-600">Terminated
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 flex items-center justify-center space-x-2">

                                    @can('leases-view')
                                        <!-- View Icon -->
                                        <i class="fas fa-eye text-gray-700 hover:text-blue-600 cursor-pointer dark:text-gray-200 dark:hover:text-blue-500"
                                            wire:navigate
                                            href="{{ route('admin.view-lease', ['transaction' => $transaction->id]) }}">
                                        </i>
                                    @endcan


                                    @can('leases-edit')
                                        <!-- Edit Icon -->
                                        <i class="fas fa-edit text-gray-700 hover:text-yellow-600 cursor-pointer dark:text-gray-200 dark:hover:text-yellow-500"
                                            wire:navigate
                                            href="{{ route('admin.edit-lease', ['transaction' => $transaction->id]) }}">
                                        </i>
                                    @endcan

                                    @can('leases-delete')
                                        <!-- Delete Icon -->
                                        <i class="fas fa-trash-alt text-gray-700 hover:text-red-600 cursor-pointer dark:text-gray-200 dark:hover:text-red-500"
                                            wire:click="confirmDelete({{ $transaction->id }})"
                                            wire:loading.attr="disabled">
                                        </i>
                                    @endcan

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="15" class="text-center py-10 text-gray-500">
                                    No leases found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="py-4 px-3 dark:bg-gray-800 dark:text-white rounded-lg">
                <div class="flex ">
                    <div class="flex space-x-4 items-center mb-3">
                        <label class="w-32 text-sm font-medium text-gray-900 dark:text-white">Per Page</label>
                        <select wire:model.live='perPage'
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5
                            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>
                {{ $transactions->links() }}
            </div>
        </div>


        <!-- Delete Confirmation Modal -->
        <x-dialog-modal wire:model.live="confirmItemDelete" type="danger">
            <x-slot name="title">
                {{ __('Delete Lease') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to delete this item?') }}
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('confirmItemDelete', false)" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3" wire:click="deleteLease" wire:loading.attr="disabled">
                    {{ __('Delete Lease') }}
                </x-danger-button>
            </x-slot>
        </x-dialog-modal>

        {{-- Cannot Delete Modal --}}
        <x-dialog-modal wire:model="cannotDeleteItem" type="ghost">
            <x-slot name="title">
                {{ __('Unable to Delete') }}
            </x-slot>

            <x-slot name="content">
                {{ __('This is an active or on-going lease and cannot be deleted.') }}
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('cannotDeleteItem', false)" wire:loading.attr="disabled">
                    {{ __('OK') }}
                </x-secondary-button>
            </x-slot>
        </x-dialog-modal>

        {{--
    <!-- Bulk Delete Confirmation Modal -->
    <x-dialog-modal wire:model.live="confirmBulkDelete">
        <x-slot name="title">
            {{ __('Delete Leases') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to delete these items?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('confirmBulkDelete', false)" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click="deleteSelectedRows" wire:loading.attr="disabled">
                {{ __('Delete Lease') }}
            </x-danger-button>
        </x-slot>
    </x-dialog-modal> --}}
    @endif
</div>
