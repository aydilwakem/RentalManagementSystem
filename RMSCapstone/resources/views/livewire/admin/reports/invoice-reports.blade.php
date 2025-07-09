<div>
    {{-- Table --}}
    <div class="overflow-x-auto p-3 mb-3">
        <div class="flex items-center justify-between p-4">
            <div class="flex gap-4 w-full">
                <div class="relative w-full">
                    {{-- Start Date --}}
                    <div class="w-full">
                        <label for="startDate" class="block mb-2 text-sm font-medium text-gray-900">
                            Start Date:</label>
                        <input type="date" wire:model.lazy="startDate" id="startDate"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                        @error('startDate')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- End Date --}}
                <div class="w-full">
                    <label for="endDate" class="block mb-2 text-sm font-medium text-gray-900">End Date:
                    </label>
                    <input type="date" wire:model.lazy="endDate" id="endDate"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    @error('endDate')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Invoice Type Filter -->
                <div class="w-full">
                    <label for="invoice_type" class="block mb-2 text-sm font-medium text-gray-900">Invoice Type:</label>
                    <select id="invoice_type" name="invoice_type" wire:model.live="invoiceTypeFilter"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                        <option value="">All</option>
                        <option value="Event_Hall">Events</option>
                        <option value="Room">Room Reservations</option>
                        <option value="House">Leases</option>
                    </select>
                </div>

                <!-- Invoice Status Filter -->
                <div class="w-full">
                    <label for="invoice_status" class="block mb-2 text-sm font-medium text-gray-900">Invoice
                        Status:</label>
                    <select id="invoice_status" name="invoice_status" wire:model.live="invoiceStatusFilter"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                        <option value="">All</option>
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                        <option value="failed">Failed</option>
                        <option value="overdue">Overdue</option>
                    </select>
                </div>

                {{-- Apply Filter Button --}}
                <div class="flex items-end">
                    <x-button icon="fa fa-filter" wire:click="applyInvoiceFilter">
                        Apply Filter
                    </x-button>
                </div>
            </div>
        </div>

        @if (!$filterApplied)
        <div class="w-full text-center py-4">
            <span class="text-green-500 font-medium">
                Please apply filters first to generate the invoice report summary.
            </span>
        </div>
        @else

        @if (!empty($filteredInvoices))
        {{-- EXPORT PDF BUTTON --}}
        <div class="flex justify-end mb-2 space-x-2 mr-4">
            {{-- EXPORT PDF BUTTON --}}
            <div class="relative">
                <x-button icon="fa-solid fa-file" wire:click="exportInvoiceSummary" wire:loading.attr="disabled">
                    Export PDF
                </x-button>

                <div wire:loading wire:target="exportInvoiceSummary"
                    class="absolute inset-0 flex items-center justify-center bg-white/70 rounded pl-4">
                    <span class="text-sm text-green-700 font-semibold pt-3">Exporting PDF...</span>
                </div>
            </div>

            {{-- EXPORT CSV BUTTON --}}
            <div class="relative">
                <x-warning-button icon="fa-solid fa-file" wire:click="exportInvoiceCsv" wire:loading.attr="disabled">
                    Export CSV
                </x-warning-button>

                <div wire:loading wire:target="exportInvoiceCsv"
                    class="absolute inset-0 flex items-center justify-center bg-white/70 rounded pl-4">
                    <span class="text-sm text-yellow-700 font-semibold">Exporting CSV...</span>
                </div>
            </div>
        </div>

        <!-- Table Content -->
        <!-- Table Body -->
        <div class="bg-white rounded-lg shadow-md overflow-x-auto border">
            <!-- Table Content -->
            <div class="overflow-x-auto">
                <table class="min-w-full text-left">

                    <thead class="text-sm text-gray-700 bg-gray-200">
                        <tr>
                            <th class="px-4 py-3">ID</th>
                            <th class="px-4 py-3 flex items-center space-x-2">
                                {{-- <input type="checkbox" class="accent-blue-600 w-4 h-4"> --}}
                                <span>Invoice ID</span>
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
                        @forelse ($filteredInvoices as $invoice)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3">
                                INV-{{ str_pad($loop->iteration, 3, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap space-x-1">
                                {{-- <input type="checkbox" class="accent-blue-600 w-4 h-4"> --}}
                                <span>{{ $invoice->invoice_number ?? 'N/A' }}</span>
                            </td>
                            <td class="px-4 py-3">
                                {{ $invoice->transaction->transactionUser->first_name ?? '' }}
                                {{ $invoice->transaction->transactionUser->last_name ?? '' }}
                            </td>
                            <td class="px-4 py-3">{{ $invoice->transaction->transaction_number ?? 'N/A' }}</td>
                            <td class="px-4 py-3">
                                {{ optional($invoice->created_at)->format('M j, Y') ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-3">
                                {{ optional($invoice->due_date)->format('M j, Y') ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-3">₱{{ number_format($invoice->sub_total, 2) }}</td>
                            <td class="px-4 py-3">₱{{ number_format($invoice->balance_due, 2) }}</td>
                            <td class="px-4 py-3">
                                {{ ucfirst($invoice->invoice_type ?? 'N/A') }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-block text-center py-1 px-2 rounded-full text-xs font-semibold
                                        {{ $invoice->invoice_status === 'pending' ? 'bg-yellow-100 text-yellow-500'
                                        : ($invoice->invoice_status === 'completed' ? 'bg-green-100 text-green-500'
                                        : ($invoice->invoice_status === 'failed' ? 'bg-red-100 text-red-500'
                                        : ($invoice->invoice_status === 'overdue' ? 'bg-pink-100 text-pink-500'
                                        : 'bg-gray-100 text-gray-500'))) }}">
                                    {{ ucfirst($invoice->invoice_status ?? 'Unknown') }}
                                </span>
                            </td>

                            {{-- <td class="px-4 py-3 space-x-1">
                                <!-- View Icon -->
                                <i class="fas fa-eye text-gray-700 hover:text-blue-600 cursor-pointer" wire:navigate
                                    href="#"></i>
                                <!-- Delete Icon -->
                                <i class="fas fa-trash-alt text-gray-700 hover:text-red-600 cursor-pointer"
                                    href="#"></i>
                            </td> --}}

                        </tr>
                        @empty
                        <tr>
                            <td colspan="15" class="text-center py-10 text-green-700 font-semibold">
                                No invoices found for this date range.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @endif
                </table>
            </div>
        </div>
        @endif
    </div>
</div>