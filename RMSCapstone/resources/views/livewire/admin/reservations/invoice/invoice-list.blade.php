<div class="min-h-[550px] container mx-auto p-6 max-w-full">

    <!-- Action Buttons -->
    {{-- <div class="flex items-center justify-between mb-4">
        <div class="flex justify-between items-center">
            <x-button icon="fas fa-plus" href="#">
                New Invoice
            </x-button>
        </div>
        <x-button class="!bg-gray-600 hover:!bg-gray-700 focus:ring focus:!ring-gray-600 focus:!ring-offset-2"
            icon="fas fa-trash" href="#">
            Deleted Invoices
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

            <!-- Invoice Status Filter -->
            <div class="flex items-center">
                <label for="invoice_status" class="w-32 text-sm font-medium text-gray-900">Invoice Status:</label>
                <select id="invoice_status" name="invoice_status" wire:model.live="invoiceStatusFilter"
                    class="w-40 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                    <option value="">All</option>
                    <option value="pending">Pending</option>
                    <option value="completed">Completed</option>
                    <option value="failed">Failed</option>
                    <option value="overdue">Overdue</option>
                </select>
            </div>


            <!-- Invoice Type Filter -->
            {{-- <div class="flex items-center mt-4">
                <label for="invoice_type" class="w-32 text-sm font-medium text-gray-900">Invoice Type:</label>
                <select id="invoice_type" name="invoice_type" wire:model="invoiceTypeFilter"
                    class="w-40 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                    <option value="">All</option>
                    @foreach ($reservationTypes as $type)
                    <option value="{{ $type->reservation_type_id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
            </div> --}}


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
                        <th class="px-4 py-3">Billing Date</th>
                        <th class="px-4 py-3">Payment Date</th>
                        <th class="px-4 py-3">Amount Paid</th>
                        <th class="px-4 py-3">Remaining Balance</th>
                        <th class="px-4 py-3">Availed Service</th>
                        <th class="px-4 py-3">Status</th>
                        {{-- <th class="px-4 py-3">Actions</th> --}}
                    </tr>
                </thead>


                <tbody>
                    @foreach ($invoices as $invoice)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap space-x-1">
                                            {{-- <input type="checkbox" class="accent-blue-600 w-4 h-4"> --}}
                                            <span>{{ $invoice->invoice_number ?? 'N/A' }}</span>
                                        </td>
                                        <td class="px-4 py-3">
                                            {{ $invoice->transaction->transactionUser->first_name ?? '' }}
                                            {{ $invoice->transaction->transactionUser->last_name ?? '' }}
                                        </td>
                                        <td class="px-4 py-3">{{ $invoice->transaction->id ?? 'N/A' }}</td>
                                        <td class="px-4 py-3">
                                            {{ optional($invoice->created_at)->format('M j, Y') ?? 'N/A' }}
                                        </td>
                                        <td class="px-4 py-3">
                                            {{ optional($invoice->due_date)->format('M j, Y') ?? 'N/A' }}
                                        </td>
                                        <td class="px-4 py-3">₱{{ number_format($invoice->sub_total, 2) }}</td>
                                        <td class="px-4 py-3">₱{{ number_format($invoice->balance_due, 2) }}</td>
                                        <td class="px-4 py-3">
                                            {{ ucfirst($invoice->transaction->reservationType->first()->name ?? 'N/A') }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <span
                                                class="inline-block text-center py-1 px-2 rounded-full text-xs font-semibold
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                {{
                        $invoice->invoice_status === 'pending' ? 'bg-yellow-100 text-yellow-500' :
                        ($invoice->invoice_status === 'completed' ? 'bg-green-100 text-green-500' :
                            ($invoice->invoice_status === 'failed' ? 'bg-red-100 text-red-500' :
                                ($invoice->invoice_status === 'overdue' ? 'bg-pink-100 text-pink-500' : 'bg-gray-100 text-gray-500')))
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                }}">
                                                {{ ucfirst($invoice->invoice_status ?? 'Unknown') }}
                                            </span>
                                        </td>

                                        {{-- <td class="px-4 py-3 space-x-1">
                                            <!-- View Icon -->
                                            <i class="fas fa-eye text-gray-700 hover:text-blue-600 cursor-pointer" wire:navigate
                                                href="#"></i>
                                            <!-- Delete Icon -->
                                            <i class="fas fa-trash-alt text-gray-700 hover:text-red-600 cursor-pointer" href="#"></i>
                                        </td> --}}

                                    </tr>
                    @endforeach

                </tbody>



            </table>
        </div>
    </div>
</div>