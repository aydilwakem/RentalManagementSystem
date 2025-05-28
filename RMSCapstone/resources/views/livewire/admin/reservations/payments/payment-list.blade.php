<div class="min-h-[550px] container mx-auto p-6 max-w-full">

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
    <div class="bg-white rounded-lg shadow-md overflow-x-auto border">
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
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 "
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
                    <label for="payment_type" class="w-32 text-sm font-medium text-gray-900">Payment Type:</label>
                    <select id="payment_type" name="payment_type" wire:model.live="paymentTypeFilter"
                        class="w-40 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                        <option value="">All</option>
                        <option value="Room Rent">Room Rent</option>
                        <option value="House Rent">House Rent</option>
                        <option value="Activity Fee">Activity Fee</option>
                        <option value="Event Hall">Event Hall</option>
                        <option value="Event Package">Event Package</option>
                        <option value="Security Deposit">Security Deposit</option>
                    </select>
                </div>

                <!-- Payment Status Filter -->
                <div class="flex items-center">
                    <label for="payment_status" class="w-32 text-sm font-medium text-gray-900">Payment Status:</label>
                    <select id="payment_status" name="payment_status" wire:model.live="paymentStatusFilter"
                        class="w-40 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                        <option value="">All</option>
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                        <option value="failed">Failed</option>
                    </select>
                </div>
            </div>

        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto">
            <table class="min-w-full text-left">

                <thead class="text-sm text-gray-700 bg-gray-200">
                    <tr>
                        <th class="px-4 py-3 flex items-center space-x-2">
                            {{-- <input type="checkbox" class="accent-blue-600 w-4 h-4"> --}}
                            <span>ID</span>
                        </th>
                        <th class="px-4 py-3">Guest Name</th>
                        <th class="px-4 py-3">Transaction No.</th>
                        <th class="px-4 py-3">Invoice No.</th>
                        <th class="px-4 py-3">Total Amount Paid</th>
                        <th class="px-4 py-3">Payment Type</th>
                        <th class="px-4 py-3">Reference No.</th>
                        <th class="px-4 py-3">Method</th>
                        <th class="px-4 py-3">Status</th>
                        {{-- <th class="px-4 py-3">Actions</th> --}}
                    </tr>
                </thead>

                <tbody>
                    @foreach ($payments as $payment)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap space-x-1">
                            {{-- <input type="checkbox" class="accent-blue-600 w-4 h-4"> --}}
                            <span>{{ $payment->id ?? 'N/A' }}</span>
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
                            {{ $payment->paymentMethod->mode_of_payment_name ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-3">
                            <span
                                class="inline-block py-1 px-2 rounded-full text-xs font-semibold 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        {{ $payment->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-500' :
                        ($payment->payment_status === 'completed' ? 'bg-green-100 text-green-500' :
                            ($payment->payment_status === 'failed' ? 'bg-red-100 text-red-500' : 'bg-gray-100 text-gray-500')) }}">
                                {{ ucfirst($payment->payment_status ?? 'Unknown') }}
                            </span>
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
                    @endforeach

                </tbody>
            </table>
        </div>



    </div>
</div>