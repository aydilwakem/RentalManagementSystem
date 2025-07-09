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
                        <input type="date" wire:model.lazy="startDate" id="startDate" required
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
                    <input type="date" wire:model.lazy="endDate" id="endDate" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    @error('endDate')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="w-full">
                    <label for="endDate" class="block mb-2 text-sm font-medium text-gray-900">Payment Type:
                    </label>
                    <select id="payment_type" name="payment_type" wire:model.live="paymentTypeFilter" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                        <option value="">All</option>
                        <option value="Room Rent">Room Rent</option>
                        <option value="House Rent">House Rent</option>
                        <option value="Activity Fee">Activity Fee</option>
                        <option value="Event Hall">Event Hall</option>
                        <option value="Event Package">Event Package</option>
                        <option value="Security Deposit">Security Deposit</option>
                        <option value="Remaining Balance">Remaining Balance</option>
                    </select>
                </div>

                {{-- Apply Filter Button --}}
                <div class="flex items-end">
                    <x-button icon="fa fa-filter" wire:click="applyPaymentFilter">
                        Apply Filter
                    </x-button>
                </div>
            </div>
        </div>

        @if (!$filterApplied)
        <div class="w-full text-center py-4">
            <span class="text-green-500 font-medium">
                No payments found. Select start date and end date to generate payments summary.
            </span>
        </div>
        @else

        @if (!empty($filteredPayments))
        {{-- Export Buttons --}}
        <div class="flex justify-end mb-2 space-x-2 mr-4">
            {{-- EXPORT PDF BUTTON --}}
            <div class="relative">
                <x-button icon="fa-solid fa-file" wire:click="exportPaymentSummary" wire:loading.attr="disabled">
                    Export PDF
                </x-button>

                <div wire:loading wire:target="exportPaymentSummary"
                    class="absolute inset-0 flex items-center justify-center bg-white/70 rounded pl-4">
                    <span class="text-sm text-green-700 font-semibold pt-3">Exporting PDF...</span>
                </div>
            </div>

            {{-- EXPORT CSV BUTTON --}}
            <div class="relative">
                <x-warning-button icon="fa-solid fa-file" wire:click="exportPaymentCsv" wire:loading.attr="disabled">
                    Export CSV
                </x-warning-button>

                <div wire:loading wire:target="exportPaymentCsv"
                    class="absolute inset-0 flex items-center justify-center bg-white/70 rounded pl-4">
                    <span class="text-sm text-yellow-700 font-semibold">Exporting CSV...</span>
                </div>
            </div>
        </div>

        <!-- Table Content -->
        <div class=" bg-white rounded-lg shadow-md overflow-x-auto border">
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
                        <th class="px-4 py-3">Amount Paid</th>
                        <th class="px-4 py-3">Payment Type</th>
                        <th class="px-4 py-3">Reference No.</th>
                        <th class="px-4 py-3">Method</th>
                        <th class="px-4 py-3">Status</th>
                        {{-- <th class="px-4 py-3">Actions</th> --}}
                    </tr>
                </thead>

                <tbody>
                    @forelse ($filteredPayments as $payment)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap space-x-1">
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
                            {{ucfirst($payment->mode_of_payment?? 'N/A') }}
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
                        <td colspan="15" class="text-center py-10 text-green-700 font-semibold">
                            No payments found for this date range.
                        </td>
                    </tr>
                    @endforelse

                </tbody>
                @endif
            </table>
        </div>
        @endif
    </div>