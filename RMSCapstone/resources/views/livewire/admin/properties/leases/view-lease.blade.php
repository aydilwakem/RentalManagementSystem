<div>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tigh dark:text-white">
            {{ __('View Lease') }}
        </h2>
    </x-slot>

    <!-- Body Container -->
    <div class="py-3">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 bg-white rounded-lg border shadow-md p-6 dark:bg-gray-700 dark:border-gray-600">

            <div class="relative flex items-center mb-4">
                <!-- Title -->
                <h2 class="text-2xl font-bold text-gray-900 w-full text-center dark:text-white">Lease:
                    {{ $transaction->invoice->transaction->transaction_number ?? 'N/A' }}</h2>

                <!-- Back Button -->
                <button onclick="window.location.href='{{ route('admin.leases') }}'" wire:navigate
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute right-0 translate-y-[-12px]">
                    <span class="leading-none translate-y-[-3px]">&times;</span>
                </button>
            </div>

            <!-- Guest Details -->
            <h3 class="text-lg font-bold text-green-800 mb-3 dark:text-green-300">Occupany Details</h3>
            <div class="bg-gray-50 rounded-lg p-6 mb-6 dark:bg-gray-600 dark:border-gray-500 border">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2 text-gray-600 dark:text-gray-200">
                    <div><strong>Property:</strong>
                        @foreach ($transaction->properties as $property)
                        {{ $property->name_number ?? 'N/A' }}<br>
                        @endforeach
                    </div>
                    <div>
                        <strong>Tenant:</strong>
                        {{ $transaction->transactionUser->first_name }}
                        {{ $transaction->transactionUser->last_name }}
                    </div>
                    <div><strong>Total Occupants:</strong> {{ $transaction->pax }}</div>
                    <div><strong>Phone Number:</strong>
                        {{ $transaction->transactionUser->contact_number ?? 'No contact number provided' }}
                    </div>
                    <div><strong>Lease Status</strong>
                        {{ ucfirst($transaction->transaction_status ?? 'No Status') }}
                    </div>

                </div>
            </div>

            <!-- Rent Details -->
            <h3 class="text-lg font-bold text-green-800 mb-3 dark:text-green-300">Rent Details</h3>
            <div class="bg-gray-50 rounded-lg p-6 mb-6 dark:bg-gray-600 dark:border-gray-500 border">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2 text-gray-600 dark:text-gray-200">
                    <div>
                        <strong>Monthly Rent:
                        </strong> @foreach ($transaction->properties as $property)
                        ₱{{ number_format ($property->amount, 2) }}<br>
                        @endforeach
                    </div>
                    <div>
                        <strong>Total Rent for Lease Term:
                        </strong>₱{{ number_format($transaction->total_amount, 2) }}
                    </div>
                    <div class="overflow-x-auto col-span-2">
                        <table class="min-w-full divide-y divide-gray-200 border dark:border-gray-500 dark:divide-gray-500">
                            <thead class="bg-green-50 dark:bg-green-200">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-sm font-medium text-gray-800 uppercase tracking-wider">
                                        Lease Start Date
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-sm font-semibold text-gray-800 uppercase tracking-wider">
                                        Lease End Date
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-sm font-semibold text-gray-800 uppercase tracking-wider">
                                        Lease Duration
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-500 dark:divide-gray-500">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $transaction->start_datetime->format('F j, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $transaction->end_datetime->format('F j, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $this->getMonthCount($transaction->start_datetime,
                                        $transaction->end_datetime) }}
                                        Months
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Invoice ? -->
            <h3 class="text-lg font-bold text-green-800 mb-3 dark:text-green-300">Invoice Details</h3>
            <div class="bg-gray-50 rounded-lg p-6 text-gray-600 dark:bg-gray-600 dark:border-gray-500 border">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2 dark:text-gray-200">
                    <div><strong>Transaction ID: </strong>
                        {{ $transaction->transaction_number ?? 'N/A' }}
                    </div>
                    <div><strong>Invoice Number: </strong> {{ $transaction->invoice->invoice_number ?? 'N/A' }}
                    </div>
                    <div><strong>Sub Total: </strong>
                        ₱{{ optional($transaction->invoice)->sub_total !== null
                        ? number_format(optional($transaction->invoice)->sub_total, 2)
                        : 'N/A' }}
                    </div>
                    <div><strong>Balance Due:
                        </strong>₱{{ optional($transaction->invoice)->sub_total !== null
                        ? number_format(optional($transaction->invoice)->balance_due, 2)
                        : 'N/A' }}
                    </div>
                    <div><strong>Due Date: </strong>
                        {{ optional(optional($transaction->invoice)->due_date)->format('F j, Y') ?? 'N/A' }}
                    </div>
                    <div><strong>Invoice Status: </strong>
                        {{ ucfirst($transaction->invoice->invoice_status ?? 'N/A') }}
                    </div>
                </div>
            </div>

            <!-- Payments  -->
            <section id="payments">
                <div class="text-left mt-6 mb-3 flex items-center gap-2">
                    <x-button wire:click="OpenCreatePaymentModal">
                        <i class="fas fa-plus mr-2"></i>
                        Create Payment
                    </x-button>

                    <!-- Info Icon with Tooltip -->
                    <div class="relative group">
                        <i class="fas fa-info-circle text-gray-500 text-sm cursor-pointer dark:text-gray-200"></i>
                        <div
                            class="absolute left-1/2 -translate-x-1/2 bottom-full mb-2 w-56 text-xs text-white bg-gray-800 p-2 rounded shadow-lg opacity-0 group-hover:opacity-100 transition-opacity z-10">
                            Create Payment is for cash payments only.
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow-lg rounded-lg border border-gray-200 p-6 dark:bg-gray-600 dark:border-gray-500">
                    <h2 class="font-semibold text-xl text-green-700 leading-tight mb-4 dark:text-green-300">
                        {{ __('Payments') }}
                    </h2>
                    @if ($payments->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse border border-gray-300 text-sm dark:text-gray-200">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">Payment ID</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">Invoice ID</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">Method</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">Amount Paid</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">Type</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">Reference No.</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">Payment Date</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">Status</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">Notes</th>
                                    {{-- <th class="border px-4 py-2 font-medium text-gray-900">Verified At</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900">Action</th> --}}
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-500 dark:text-gray-200 divide-y divide-gray-200 dark:divide-gray-500">
                                @foreach ($payments as $payment)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-400">
                                    <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-600">{{ $payment->id }}</td>
                                    <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-600">{{ $payment->invoice->invoice_number }}
                                    </td>
                                    <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-600">
                                        {{ ucfirst($payment->mode_of_payment) }}</td>
                                    <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-600">
                                        ₱{{ number_format($payment->amount_paid, 2) }}</td>
                                    <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-600">
                                        {{ ucfirst($payment->payment_type) }}</td>
                                    <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-600">
                                        {{ $payment->payment_reference_number ?? 'N/A' }}</td>
                                    <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-600">
                                        {{ $payment->payment_date ?? 'N/A' }}</td>
                                    <td class="border px-4 py-2 dark:text-gray-200 dark:border-gray-600">
                                        <span
                                            class="inline-block py-1 px-2 rounded-full text-xs font-semibold
                                                {{ $payment->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-500' : '' }}
                                                {{ $payment->payment_status === 'failed' ? 'bg-red-100 text-red-500' : '' }}
                                                {{ $payment->payment_status === 'completed' ? 'bg-green-100 text-green-500' : '' }}">
                                            {{ ucfirst($payment->payment_status) }}
                                        </span>
                                    </td>
                                    <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-600">{{ $payment->notes ?? '-' }}
                                    </td>
                                    {{-- <td class="border px-4 py-2 text-gray-700">
                                        {{ $payment->verified_at ?? 'To be verified' }}</td>
                                    <td class="border px-4 py-2 space-x-2">
                                        @if ($payment->payment_status === 'pending')
                                        <a href="{{ route('admin.view-payment-receipt', ['payment' => $payment->id]) }}"
                                            class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white font-semibold text-center py-2 px-4 rounded text-xs">
                                            Verify Receipt
                                        </a>
                                        @elseif($payment->payment_status === 'completed' || $payment->payment_status ===
                                        'failed')
                                        <a href="{{ route('admin.view-payment-receipt', ['payment' => $payment->id]) }}"
                                            class="inline-block bg-green-500 hover:bg-green-700 text-white font-semibold text-center py-2 px-4 rounded text-xs">
                                            View Receipt
                                        </a>
                                        @endif
                                    </td> --}}
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-gray-600 italic dark:text-white">No payments found for this invoice.</p>
                    @endif
                </div>
            </section>


            <!---------------------------- MODALS ---------------------------------------->
            <div>
                @if ($createPaymentModal)
                <div id="guestModal" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
                    <div class="bg-white p-6 rounded-lg shadow-lg w-[90%] md:w-[600px] max-h-[90vh] overflow-y-auto dark:bg-gray-700 dark:text-gray-200">
                        <h2 class="text-lg font-semibold mb-4 text-green-700 dark:text-green-300">Add Payment</h2>

                        <!-- Amount Paid -->
                        <div class="mt-4">
                            <label class="block text-sm text-gray-700 dark:text-gray-200">Amount Paid <span class="text-red-500">*</span></label>
                            <input type="number" wire:model="amount_paid"
                                placeholder="Ex. 10,000.00"
                                class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md
                                dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400" required>
                            @error('amount_paid')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Payment Date -->
                        <div class="mt-4">
                            <label class="block text-sm text-gray-700 dark:text-gray-200">Payment Date <span class="text-red-500">*</span></label>
                            <input type="date" wire:model="payment_date"
                                class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md
                                dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400" required>
                            @error('payment_date')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Payment Type -->
                        <div class="mt-4">
                            <label class="block text-sm text-gray-700 dark:text-gray-200">Payment Type <span class="text-red-500">*</span></label>
                            <select wire:model="payment_type"
                                class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md
                                dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400" required>
                                <option value="">Select Payment Type</option>
                                <option value="Room Rent">Room Rent</option>
                                <option value="House Rent">House Rent</option>
                                <option value="Activity Fee">Activity Fee</option>
                                <option value="Event Hall">Event Hall</option>
                                <option value="Event Package">Event Package</option>
                                <option value="Security Deposit">Security Deposit</option>
                                <option value="Remaining Balance">Remaining Balance</option>
                            </select>
                            @error('payment_type')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div class="mt-4">
                            <label class="block text-sm text-gray-700 dark:text-gray-200">Notes</label>
                            <input type="text" wire:model="notes"
                                placeholder="Optional notes about the payment"
                                class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md
                                dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                            @error('notes')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-between items-center gap-2 mt-6">
                            <x-button type="button" wire:click="CloseCreatePaymentModal"
                                class="!bg-gray-200 !text-black hover:!bg-gray-300 focus:!ring-2 focus:!ring-gray-400 focus:!outline-none">
                                Cancel
                            </x-button>
                            <x-button type="button" wire:click="CreatePayment">
                                Save Changes
                            </x-button>
                        </div>

                    </div>
                </div>
                @endif
            </div>



            <!-- Action Buttons -->
            <div class="flex items-center justify-between space-x-4 mt-6 mb-3">
                <!-- Delete -->
                <x-danger-button type="button" icon="fas fa-trash" wire:click="confirmDelete({{ $transaction->id }})">
                    Delete
                </x-danger-button>

                <div class="space-x-3">
                    <!-- Edit -->
                    <x-ghost-button type="button" icon="fas fa-pen-to-square" wire:navigate
                        href="{{ route('admin.edit-lease', ['transaction' => $transaction->id]) }}">
                        Edit
                    </x-ghost-button>

                    <!-- Export PDF -->
                    <x-button icon="fa-solid fa-file" wire:click="exportLeaseDetails">
                        Export PDF
                    </x-button>
                </div>
                <!-- Edit -->
            </div>

            <x-dialog-modal wire:model.live="confirmItemDelete" type="danger">
                <x-slot name="title">
                    {{ __('Delete Lease') }}
                </x-slot>

                <x-slot name="content">
                    {{ __('Are you sure you want to delete this lease?') }}
                </x-slot>

                <x-slot name="footer">
                    <x-secondary-button wire:click="$set('confirmItemDelete', false)" wire:loading.attr="disabled">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-danger-button class="ms-3" wire:click="deleteLease({{ $transaction->id }})"
                        wire:loading.attr="disabled">
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
        </div>
    </div>
</div>
