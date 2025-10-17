<div class=" container mx-auto p-6 max-w-full">

    <!-- Action Buttons -->
    {{-- <div class="flex items-center justify-between mb-4">
        <div class="flex justify-between items-center">
            <x-button icon="fas fa-plus" href="#">
                New Payment
            </x-button>
        </div>
        <x-button class="!bg-gray-600 hover:!bg-gray-700 focus:ring focus:!ring-gray-600 focus:!ring-offset-2"
            icon="fas fa-trash" href="#">
            Deleted Payments
        </x-button>
    </div> --}}

    <!-- Table BOdy -->
    <div
        class="bg-white rounded-lg shadow-md overflow-x-auto border dark:bg-gray-800 dark:border-gray-700 dark:text-white">
        <!-- Header -->
        <div class="flex items-center justify-between p-4">
            <div class="flex">

                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg aria-hidden="true" class="w-5 h-5 text-gray-500" fill="currentColor" viewbox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <!-- Search-->
                    <input wire:model.live.debounce.300ms="search" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full pl-10 p-2
                        dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        placeholder="Search" required="">
                </div>

                <!-- Bulk Actions -->
                {{-- <div class="relative inline-block text-left ml-2" x-data="{ open: false }">
                    <button @click="open = !open" type="button"
                        class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Actions
                        <svg class="-mr-1 ml-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false"
                        class="origin-top-right absolute right-0 mt-2 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                        <div class="py-1">
                            <a href="#" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Bulk
                                Delete</a>
                        </div>
                    </div>
                </div> --}}
            </div>

            <div class="flex items-center space-x-8">
                <!-- Payment Type Filter -->
                <div class="flex items-center">
                    <label for="payment_type" class="w-32 text-sm font-medium text-gray-900 dark:text-gray-200">Payment
                        Type:</label>
                    <select id="payment_type" name="payment_type" wire:model.live="paymentTypeFilter" class="w-40 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5
                        dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        <option value="">All</option>
                        <option value="Room Rent">Room Rent</option>
                        <option value="House Rent">House Rent</option>
                        <option value="Activity Fee">Activity Fee</option>
                        <option value="Event Hall">Event Hall</option>
                        <option value="Event Package">Event Package</option>
                        <option value="Security Deposit">Security Deposit</option>
                        <option value="Remaining Balance">Remaining Balance</option>
                        <option value="Merchandise">Merchandise</option>
                        <option value="Accommodation Fully Paid">Accommodation Fully Paid</option>
                        <option value="Accommodation Downpayment">Accommodation Downpayment</option>
                        <option value="Accommodation Balance">Accommodation Balance</option>
                    </select>
                </div>

                <!-- Payment Status Filter -->
                <div class="flex items-center">
                    <label for="payment_status"
                        class="w-32 text-sm font-medium text-gray-900 dark:text-gray-200">Payment Status:</label>
                    <select id="payment_status" name="payment_status" wire:model.live="paymentStatusFilter" class="w-40 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5
                        dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        <option value="">All</option>
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                        <option value="failed">Failed</option>
                    </select>
                </div>
            </div>

        </div>

        <!-- Table Content -->
        <div wire:loading wire:target="search, paymentTypeFilter, paymentStatusFilter"
            class="w-full flex items-center justify-center min-h-[50px] relative mt-24 mb-24">
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
        <div class="overflow-x-auto">
            <table class="min-w-full text-left">
                <thead wire:loading.remove wire:target="search, paymentTypeFilter, paymentStatusFilter"
                    class="text-sm text-gray-700 bg-gray-200 dark:bg-gray-800 dark:text-white dark:border-t dark:border-gray-700">
                    <tr>
                        <th class="px-4 py-3 flex items-center space-x-2">
                            {{-- <input type="checkbox" class="accent-blue-600 w-4 h-4"> --}}
                            <span>ID</span>
                        </th>
                        <th class="px-4 py-3">Guest Name</th>
                        <th class="px-4 py-3">Transaction No.</th>
                        <th class="px-4 py-3">Invoice No.</th>
                        <th class="px-4 py-3">Amount Paid</th>
                        <th class="px-4 py-3">Payment Type</th>
                        <th class="px-4 py-3">Reference No.</th>
                        <th class="px-4 py-3">Method</th>
                        <th class="px-4 py-3">Status</th>
                        {{-- <th class="px-4 py-3">Actions</th> --}}
                    </tr>
                </thead>
                <tbody wire:loading.remove wire:target="search, paymentTypeFilter, paymentStatusFilter"
                    class="dark:bg-gray-700">
                    @forelse ($payments as $payment)
                    <tr
                        class="border-b hover:bg-gray-50 dark:hover:bg-gray-600 dark:border-gray-700 odd:dark:bg-gray-700 even:dark:bg-gray-800">
                        <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{-- <input type="checkbox" class="accent-blue-600 w-4 h-4"> --}}
                            <span>{{ $loop->iteration }}</span>
                        </td>
                        <td class="px-4 py-3">
                            {{ $payment->invoice->transaction->transactionUser->first_name ?? '' }}
                            {{ $payment->invoice->transaction->transactionUser->last_name ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-3">
                            {{ $payment->invoice->transaction->transaction_number ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-3">
                            {{ $payment->invoice->invoice_number ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-3">
                            ₱{{ number_format($payment->amount_paid, 2) }}
                        </td>
                        <td class="px-4 py-3">
                            {{ ucfirst($payment->payment_type ?? 'Unknown') }}
                        </td>
                        <td class="px-4 py-3">
                            {{ $payment->payment_reference_number ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-3">
                            {{ ucfirst($payment->paymentMethod?->mode_of_payment_name ?? $payment->mode_of_payment ??
                            'N/A') }}
                        </td>
                        <td class="px-4 py-3">
                            @if ($payment->payment_status === 'pending')
                            <span
                                class="inline-block py-1 px-2 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-600">
                                Pending
                            </span>
                            @elseif ($payment->payment_status === 'completed')
                            <span
                                class="inline-block py-1 px-2 rounded-full text-xs font-semibold bg-green-100 text-green-500">
                                Completed
                            </span>
                            @elseif ($payment->payment_status === 'failed')
                            <span
                                class="inline-block py-1 px-2 rounded-full text-xs font-semibold bg-red-100 text-red-500">
                                Failed
                            </span>
                            @else
                            <span
                                class="inline-block py-1 px-2 rounded-full text-xs font-semibold bg-gray-100 text-gray-500">
                                Unknown Status
                            </span>
                            @endif
                        </td>
                        {{-- <td class="px-4 py-3 space-x-1">
                            <!-- View Icon -->
                            <i class="fas fa-eye text-gray-700 hover:text-blue-600 cursor-pointer" wire:navigate
                                href="#">
                            </i>
                            <!-- Delete Icon -->
                            <i class="fas fa-trash-alt text-gray-700 hover:text-red-600 cursor-pointer" href="#">
                            </i>
                        </td> --}}
                    </tr>
                    @empty
                    <tr>
                        <td colspan="15" class="text-center py-10 text-gray-500">
                            No payments found.
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>



    </div>
</div>