<div class="min-h-[550px] container mx-auto p-6 ">

    @if ($transactions->isEmpty())
        <!-- Empty Table Message -->
        <div class="text-center py-10">
            <p class="text-gray-500 text-lg font-semibold">No transactions yet.<br> Click "Create Transaction" to add a
                new transaction.</p>
            <x-button class="mt-4" href="" icon="fas fa-plus" wire:navigate>
                Create Transaction
            </x-button>
        </div>
    @else
        <div>
            <!-- Create Room Button -->
            @can('maintenance-create')
                <div class="flex items-center justify-between p-4">
                    <x-button icon="fas fa-plus" href="">
                        New Transaction
                    </x-button>
                </div>
            @endcan
            {{-- Display Session Message --}}
            @if (session('message'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                    class="fixed top-4 left-1/2 transform -translate-x-1/2 px-4 py-2 rounded-lg shadow-lg
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                {{ session('alert-type') === 'success' ? 'bg-red-500 text-white' : 'bg-green-500 text-white' }}">
                    {{ session('message') }}
                </div>
            @endif
            <!-- Navigation Tabs -->
            <ul class="flex flex-wrap text-sm font-medium text-center text-gray-600 border-gray-300">
                <li class="me-2">
                    <a href="{{ route('admin.view-new-transactions') }}"
                        class="inline-block p-4 {{ Route::is('admin.view-new-transactions') ? 'text-green-700 bg-green-100 font-semibold rounded-t-lg' : 'hover:text-green-700 hover:bg-green-50 rounded-t-lg' }}">
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
                        On-Going Reservations
                    </a>
                </li>
                <li class="me-2">
                    <a href="{{ route('admin.view-old-transactions') }}"
                        class="inline-block p-4 {{ Route::is('admin.view-old-transactions') ? 'text-green-700 bg-green-100 font-semibold rounded-t-lg' : 'hover:text-green-700 hover:bg-green-50 rounded-t-lg' }}">
                        Old Reservations
                    </a>
                </li>
            </ul>

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


                            <th scope="col" class="px-4 py-3" wire:click="setSortBy('check_in_date')">
                                <button class="flex items-center">
                                    Check-in
                                    @if ($sortBy !== 'check_in_date')
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

                            <th scope="col" class="px-4 py-3" wire:click="setSortBy('check_out_date')">
                                <button class="flex items-center">
                                    Check-out
                                    @if ($sortBy !== 'check_out_date')
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

                            <th scope="col" class="px-4 py-3 text-center">Receipt Status</th>
                            <th scope="col" class="px-4 py-3 text-center">Action</th>

                        </tr>
                    </thead>
                    <tbody class="text-left">
                        @foreach ($transactions as $transaction)
                            <tr class="border-b">
                                <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                                    {{ $transaction->id }}
                                </th>
                                <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                                    {{ $transaction->first_name }} {{ $transaction->last_name }}
                                </th>
                                <td class="px-4 py-3"> {{ $transaction->pax }}</td>
                                <td class="px-4 py-3"> {{ $transaction->room->name }}</td>
                                <td class="px-4 py-3"> {{ $transaction->check_in_date }}</td>
                                <td class="px-4 py-3"> {{ $transaction->check_out_date }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span
                                        class="cursor-pointer font-semibold
                                               {{ $transaction->isPaid ? 'text-green-600' : 'text-yellow-500' }}
                                               hover:underline"
                                        wire:click="confirmReceipt({{ $transaction->id }})"
                                        wire:loading.attr="disabled">
                                        {{ $transaction->isPaid ? 'Confirmed' : 'Confirm Receipt' }}
                                    </span>
                                </td>



                                <td class="px-4 py-3 flex items-center justify-center space-x-3">

                                    <!-- View Icon -->

                                    <i class="fas fa-eye text-gray-700 hover:text-blue-600 cursor-pointer"
                                        wire:navigate href="">
                                    </i>


                                    <!-- Edit Icon -->

                                    <i class="fas fa-edit text-gray-700 hover:text-yellow-600 cursor-pointer"
                                        wire:navigate href="">
                                    </i>

                                    <!-- Delete Icon -->
                                    <i class="fas fa-trash-alt text-gray-700 hover:text-red-600 cursor-pointer"
                                        wire:click="confirmDelete({{ $transaction->id }})"
                                        wire:loading.attr="disabled">
                                    </i>

                                    <!-- Confirm Reservation Icon -->
                                    <i class="fa-solid fa-circle-check
                                                                                                                                                        {{ $transaction->isPaid ? 'text-green-600 cursor-pointer hover:text-green-700' : 'text-gray-400 cursor-not-allowed' }}"
                                        @if (!$transaction->isPaid) disabled @endif
                                        wire:click.prevent="{{ $transaction->isPaid ? "confirmReservation($transaction->id)" : '' }}"
                                        wire:loading.attr="disabled">
                                    </i>

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
                        <x-secondary-button wire:click="$set('confirmItemDelete', false)"
                            wire:loading.attr="disabled">
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
                                <img src="{{ asset('storage/' . $selectedTransaction->payment_screenshot) }}"
                                    alt="Payment Screenshot" class="w-64 h-auto mb-4">
                            </div>

                            <!-- Payment Details Below -->
                            <div class="text-left">
                                <p class="text-lg font-semibold">Name: {{ $selectedTransaction->first_name ?? 'N/A' }}
                                </p>
                                <p class="text-lg font-semibold">Payment Method:
                                    {{ $selectedTransaction->paymentMethod->mode_of_payment_name ?? 'N/A' }}</p>
                                <p class="text-lg font-semibold">Payment Reference:
                                    {{ $selectedTransaction->payment_reference_number ?? 'N/A' }}</p>
                            </div>
                        @else
                            {{ __('No payment screenshot available.') }}
                        @endif
                    </x-slot>
                    <p></p>

                    <x-slot name="footer">
                        <x-secondary-button wire:click="$set('confirmItemReceipt', false)"
                            wire:loading.attr="disabled">
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
