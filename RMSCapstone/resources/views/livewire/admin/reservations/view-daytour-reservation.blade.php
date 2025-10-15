<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white mb-1">
            {{ __('View Day Tour Reservation') }}
        </h2>
        <!-- Navigation -->
        <x-breadcrumbs :items="[
            ['label' => 'Day Tour Reservations', 'url' => route('admin.daytour-reservations-list')],
            [
                'label' => 'View Day Tour',
                'url' => route('admin.view-daytour-reservation', ['transaction' => $this->transaction->id]),
            ],
        ]" />
    </x-slot>

    <div class="py-1">
        <div class="max-w-7xl mx-auto sm:px-6 space-y-6">
            <div class="flex space-x-2">
                <!---------------------------- EXPORT DETAILS ---------------------------------------->
                {{-- <x-button wire:click="exportDayTourDetails">
                    <span wire:loading wire:target="exportDayTourDetails" class="mr-2">
                        <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                            </path>
                        </svg>
                    </span>

                    <i class="fas fa-file mr-2" wire:loading.remove wire:target="exportDayTourDetails"></i>

                    <span wire:loading.remove wire:target="exportDayTourDetails">
                        Export PDF
                    </span>
                </x-button> --}}

                <!------------------------- GENERATE RECEIPT ---------------------------------->
                @if ($transaction->transaction_status == 'done')
                    <div>
                        @if (is_null($transaction->invoice->receipt))
                            <x-button wire:click="GenerateReceipt" wire:loading.attr="disabled"
                                wire:target="GenerateReceipt">
                                <span wire:loading wire:target="GenerateReceipt" class=" items-center gap-2">
                                    <span>Generating...</span>
                                </span>
                                <span wire:loading.remove wire:target="GenerateReceipt">
                                    <i class="fas fa-receipt"></i>
                                    Generate Acknowledgement Receipt
                                </span>
                            </x-button>
                        @else
                            <x-button wire:click="ShowReceipt" icon="fas fa-eye">
                                View Receipt
                            </x-button>
                        @endif
                    </div>
                @endif
            </div>

            <!---------------------------- GUEST DETAILS ---------------------------------------->
            <div class="bg-white shadow-lg rounded-lg border border-gray-200 p-6 dark:bg-gray-700 dark:border-gray-600">
                <h2 class="font-semibold text-xl text-green-700 leading-tight mb-4 dark:text-green-300">
                    {{ __('Guest Details') }}
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-gray-700 dark:text-gray-200">
                    <div><strong>Guest Name:</strong></div>
                    <div>
                        {{ $transaction->transactionUser->first_name }}
                        {{ $transaction->transactionUser->middle_name }}
                        {{ $transaction->transactionUser->last_name }}
                        {{ $transaction->transactionUser->suffix }}
                    </div>
                    <div><strong>Email:</strong></div>
                    <div>{{ $transaction->transactionUser->email }}</div>
                    <div><strong>Contact Number:</strong></div>
                    <div>{{ $transaction->transactionUser->contact_number }}</div>
                    <div><strong>Company Name:</strong></div>
                    <div>{{ $transaction->transactionUser->company_name ?? 'Not provided' }}</div>
                    <div><strong>Country:</strong></div>
                    <div>{{ $transaction->transactionUser->country ?? 'Not provided' }}</div>
                </div>
            </div>

            <!---------------------- ADDITIONAL GUESTS DETAILS ---------------------------------->
            {{-- <div class="bg-white shadow-lg rounded-lg border border-gray-200 p-6 dark:bg-gray-700 dark:border-gray-600">
                <div class="text-center justify-between flex mb-4">
                    <h2 class="font-semibold text-xl text-green-700 leading-tight dark:text-green-300">
                        {{ __('Additional Guests Details') }}
                    </h2>
                    <x-button wire:click="openModal('guest')" icon="fas fa-user-plus">
                        Add Guest
                    </x-button>
                </div>
                @if ($guestDetails->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse border border-gray-300 text-sm text-center">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500 text-left">Full Name</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">Type</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">Gender</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">Citizenship</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">Country</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-600">
                                @foreach ($guestDetails as $guestDetail)
                                    <tr>
                                        <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500 text-left">
                                            {{ $guestDetail->first_name }}
                                            {{ $guestDetail->middle_name }}
                                            {{ $guestDetail->last_name }}
                                            {{ $guestDetail->suffix }}
                                        </td>
                                        <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                            {{ ucfirst($guestDetail->guestType->name) ?? 'N/A' }}
                                        </td>
                                        <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                            {{ ucfirst($guestDetail->gender) ?? 'N/A' }}
                                        </td>
                                        <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                            {{ ucfirst($guestDetail->residency) }}</td>
                                        <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                            {{ ucfirst($guestDetail->country_of_origin) }}</td>
                                        <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500 space-x-3">
                                            <button wire:click="editGuest({{ $guestDetail->id }})"
                                                class="text-yellow-600 hover:text-yellow-700 dark:text-yellow-400 dark:hover:text-yellow-500"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button wire:click="deleteGuest({{ $guestDetail->id }})"
                                                class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-500"
                                                title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-600 italic dark:text-gray-200 col-span-7 text-center">No additional guests found for this day tour.</p>
                @endif
            </div> --}}

            <!---------------------------- TRANSACTION DETAILS --------------------------------->
            <div class="bg-white shadow-lg rounded-lg border border-gray-200 p-6 dark:bg-gray-700 dark:border-gray-600">
                <h2 class="font-semibold text-xl text-green-700 leading-tight mb-4 dark:text-green-300">
                    {{ __('Day Tour Details') }}
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-gray-700 dark:text-gray-200">
                    <div class="col-span-full flex flex-wrap items-center gap-4">
                        <div class="flex flex-col">
                            <strong>Transaction Status:</strong>
                            <div class="mt-1">
                                @if ($transaction->transaction_status === 'pending')
                                    <span class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-gray-100 text-gray-600">Awaiting Payment</span>
                                @elseif ($transaction->transaction_status === 'reserved')
                                    <span class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-blue-100 text-blue-500">Pending Verification</span>
                                @elseif ($transaction->transaction_status === 'receipt_verified')
                                    <span class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-cyan-100 text-cyan-500">Payment Verified</span>
                                @elseif ($transaction->transaction_status === 'confirmed')
                                    <span class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-emerald-100 text-emerald-600">Confirmed</span>
                                @elseif ($transaction->transaction_status === 'ongoing')
                                    <span class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-600">On-Going</span>
                                @elseif ($transaction->transaction_status === 'done')
                                    <span class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-indigo-100 text-indigo-600">Completed</span>
                                @elseif ($transaction->transaction_status === 'no_show')
                                    <span class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-pink-100 text-pink-500">No Show</span>
                                @elseif ($transaction->transaction_status === 'terminated')
                                    <span class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-rose-100 text-rose-600">Terminated</span>
                                @elseif ($transaction->transaction_status === 'expired')
                                    <span class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-orange-100 text-orange-500">Expired</span>
                                @elseif ($transaction->transaction_status === 'cancelled')
                                    <span class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-red-100 text-red-600">Cancelled</span>
                                @else
                                    {{ ucfirst($transaction->transaction_status) }}
                                @endif
                            </div>
                        </div>
                    </div>

                    <div>
                        <strong>Transaction ID:</strong>
                        <div>{{ $transaction->transaction_number }}</div>
                    </div>

                    <div>
                        <strong>Tour Date:</strong>
                        <div>{{ \Carbon\Carbon::parse($transaction->start_datetime)->format('F j, Y') }}</div>
                    </div>

                    <div>
                        <strong>Reservation Created At:</strong>
                        <div>{{ $transaction->created_at->format('F j, Y') }} at {{ $transaction->created_at->format('g:i A') }}</div>
                    </div>

                    <div>
                        <strong>Total Guests:</strong>
                        <div>{{ $transaction->pax }}</div>
                    </div>

                    <div>
                        <strong>Total Adults:</strong>
                        <div>{{ $transaction->total_adults }}</div>
                    </div>
                    <div>
                        <strong>Total Kids:</strong>
                        <div>{{ $transaction->total_kids }}</div>
                    </div>

                    <div>
                        <strong>Subtotal:</strong>
                        <div>₱{{ number_format($transaction->sub_total, 2) }}</div>
                    </div>

                    <div>
                        <strong>Convenience Fee:</strong>
                        <div>₱{{ number_format($transaction->convenience_fee, 2) }}</div>
                    </div>

                    <div>
                        <strong>Total Amount:</strong>
                        <div>₱{{ number_format($transaction->total_amount, 2) }}</div>
                    </div>

                    <div>
                        <strong>Heard From:</strong>
                        <div>{{ $transaction->heard_from }}</div>
                    </div>

                    <div>
                        <strong>Reservation Source:</strong>
                        <div>{{ $transaction->reservation_source }}</div>
                    </div>

                </div>
            </div>

            <!----------------------------- INVOICE -------------------------------------------->
            <div class="bg-white shadow-lg rounded-lg border border-gray-200 p-6 dark:bg-gray-700 dark:border-gray-600">
                <h2 class="font-semibold text-xl text-green-700 leading-tight mb-4 dark:text-green-300">
                    {{ __('Invoice Details') }}
                </h2>

                @if ($invoice)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-gray-700 dark:text-gray-200">
                        <div>
                            <div><strong>Invoice ID:</strong></div>
                            <div>{{ $invoice->invoice_number }}</div>
                        </div>

                        <div>
                            <div><strong>Due Date:</strong></div>
                            <div>
                                {{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('F j, Y') : 'Not yet set' }}
                            </div>
                        </div>

                        <div>
                            <div><strong>Grand Total:</strong></div>
                            <div class="font-semibold">₱{{ number_format($this->invoice->sub_total, 2) }}</div>
                        </div>

                        <div>
                            <div><strong>Amount Paid:</strong></div>
                            <div>₱{{ number_format($invoice->amount_paid, 2) }}</div>
                        </div>

                        <div>
                            <div><strong>Balance Due:</strong></div>
                            <div>₱{{ number_format($this->invoice->balance_due, 2) }}</div>
                        </div>

                        <div>
                            <div><strong>Invoice Status:</strong></div>
                            <div>
                                @if ($invoice->invoice_status === 'pending')
                                    <span class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-600">Pending</span>
                                @elseif ($invoice->invoice_status === 'complete' || $invoice->invoice_status === 'completed')
                                    <span class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-green-100 text-green-600">Completed</span>
                                @elseif ($invoice->invoice_status === 'failed')
                                    <span class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-red-100 text-red-600">Failed</span>
                                @elseif ($invoice->invoice_status === 'overdue')
                                    <span class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-orange-100 text-orange-600">Overdue</span>
                                @endif
                            </div>
                        </div>

                        <div>
                            <div><strong>Completed At:</strong></div>
                            <div>
                                {{ $invoice->completed_at ? \Carbon\Carbon::parse($invoice->completed_at)->format('F j, Y') : 'Not yet completed' }}
                            </div>
                        </div>
                    </div>
                    <hr class="py-2 mt-4">
                    <div class="flex justify-between items-center">
                        <!------------------------  REQUEST REMAINING BALANCE ------------------------------------->
                        <div>
                            @if ($invoice->balance_due > 0 && !$invoice->requested_remaining_balance)
                                <x-button wire:click="requestRemainingBalance" wire:loading.attr="disabled">
                                    <div class="flex items-center justify-center">
                                        <span wire:loading class="mr-2" wire:target="requestRemainingBalance">
                                            <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z"></path>
                                            </svg>
                                        </span>
                                        <i class="fas fa-money-bill-wave mr-2" wire:loading.remove wire:target="requestRemainingBalance"></i>
                                        <span wire:loading.remove wire:target="requestRemainingBalance">Email Balance Request</span>
                                    </div>
                                </x-button>
                            @elseif ($invoice->balance_due > 0 && $invoice->requested_remaining_balance)
                                <p class="text-gray-500 italic">Waiting for guest to pay remaining balance...</p>
                            @endif
                        </div>

                        <!------------------------  MANUAL PWD/SENIOR DISCOUNT ------------------------------------->
                        @if (!$this->discountsApplied)
                            <x-button wire:click="openDiscountModal" icon="fas fa-percent">
                                Add PWD/SENIOR DISCOUNT
                            </x-button>
                        @endif
                        <!--------------------  END OF MANUAL PWD/SENIOR DISCOUNT ---------------------------------->
                    </div>

                    <!-- Items Table -->
                    <div class="mt-3">
                        <table class="min-w-full border-collapse border border-gray-300 text-sm text-left">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">#</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">Item & Description</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">Qty</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">Unit Cost</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">Amount</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">Timestamp</th>
                                </tr>
                            </thead>

                            <tbody class="bg-white text-gray-700 dark:bg-gray-600 dark:text-gray-200">
                                @php $rowNumber = 1; @endphp
                                @foreach ($allItems as $item)
                                    <tr>
                                        <td class="border px-4 py-2 dark:border-gray-500">{{ $rowNumber++ }}</td>
                                        <td class="border px-4 py-2 dark:border-gray-500">{{ $item['name'] }}</td>
                                        <td class="border px-4 py-2 text-center dark:border-gray-500">{{ $item['pax'] }}</td>
                                        <td class="border px-4 py-2 text-center dark:border-gray-500">₱{{ number_format($item['amount'], 2) }}</td>
                                        <td class="border px-4 py-2 text-center dark:border-gray-500">₱{{ number_format($item['total'], 2) }}</td>
                                        <td class="border px-4 py-2 text-center dark:border-gray-500">
                                            <span title="{{ $item['created_at']->format('F j, Y - g:i A') }}">
                                                {{ $item['created_at']->diffForHumans() }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Subtotal -->
                        <div class="mt-2 mb-1 flex justify-between font-semibold text-base text-gray-700">
                            <span>Subtotal:</span>
                            <span>₱{{ number_format($this->computeBaseSubtotal(), 2) }}</span>
                        </div>

                        <!-- Discounts applied -->
                        @if ($invoice->discounts->count() > 0)
                            <div class="mb-1 text-gray-600 text-sm border-t pt-2">
                                @foreach ($invoice->discounts->groupBy('discount_type_id') as $discounts)
                                    @php
                                        $type = $discounts->first()->discountType;
                                        $count = $discounts->sum('quantity');
                                        $totalValue = $discounts->sum('discount_value');
                                    @endphp

                                    <div class="flex justify-between items-center mb-1">
                                        <span>
                                            - {{ strtoupper($type->name) }} x {{ $count }}
                                            @if ($type->type === 'percent')
                                                ({{ $type->rate }}%)
                                            @else
                                                (Fixed)
                                            @endif
                                        </span>

                                        <div class="flex items-center space-x-2">
                                            <span>- ₱{{ number_format($totalValue, 2) }}</span>

                                            <button
                                                wire:click="removeDiscount({{ $invoice->id }}, {{ $type->id }})"
                                                class="text-red-500 hover:text-red-700 text-xs"
                                                title="Remove discount">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Subtotal after discount -->
                            <div class="flex justify-between font-semibold text-base mt-2 text-gray-700">
                                <span>Subtotal after discount:</span>
                                <span>₱{{ number_format($this->computeBaseSubtotalAfterDiscount(), 2) }}</span>
                            </div>
                        @endif

                        @if ($this->computeConvenienceFeeTotal() > 0)
                            <!-- Convenience Fee -->
                            <div class="flex justify-between font-semibold text-base mb-2 text-gray-700">
                                <span>Convenience Fee:</span>
                                <span>₱{{ number_format($this->computeConvenienceFeeTotal(), 2) }}</span>
                            </div>
                        @endif

                        <hr>

                        <!-- Grand Total -->
                        <div class="flex justify-between font-bold text-base mt-2 text-green-700">
                            <span>Grand Total:</span>
                            <span>₱{{ number_format($this->invoice->sub_total, 2) }}</span>
                        </div>

                        <!-- Amount Paid -->
                        <div class="flex justify-between font-semibold text-base {{ $this->invoice->amount_paid == $this->invoice->sub_total ? 'text-green-700' : 'text-yellow-500' }}">
                            <span>Amount Paid:</span>
                            <span>₱{{ number_format($this->invoice->amount_paid, 2) }}</span>
                        </div>

                        @if ($this->invoice->amount_paid > $this->invoice->sub_total)
                            <div class="flex justify-between font-semibold text-base text-blue-600">
                                <span>Change / Overpayment:</span>
                                <span>₱{{ number_format($this->invoice->amount_paid - $this->invoice->sub_total, 2) }}</span>
                            </div>
                        @else
                            <div class="flex justify-between font-semibold text-base {{ $this->invoice->balance_due == 0 ? 'text-green-700' : 'text-red-500' }}">
                                <span>Balance Due:</span>
                                <span>₱{{ number_format($this->invoice->balance_due, 2) }}</span>
                            </div>
                        @endif
                    </div>
                @else
                    <p class="text-gray-600 italic">No invoice found for this day tour.</p>
                @endif
            </div>

            <!----------------------------- PAYMENTS -------------------------------------->
            <section id="payments">
                <div class="bg-white shadow-lg rounded-lg border border-gray-200 p-6 dark:bg-gray-700 dark:border-gray-600">
                    <div class="justify-between flex items-center">
                        <h2 class="font-semibold text-xl text-green-700 leading-tight mb-4 dark:text-green-300">
                            Payments (₱{{ number_format($this->invoice->amount_paid, 2) }})
                        </h2>
                        <div class="text-left mb-4 flex items-center gap-2">
                            <x-button wire:click="OpenCreatePaymentModal">
                                <i class="fas fa-plus mr-2"></i>
                                Create Payment
                            </x-button>
                        </div>
                    </div>
                    @if ($payments->isNotEmpty())
                        <div class="">
                            <table class="min-w-full border-collapse border border-gray-300 text-sm">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <th class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">ID</th>
                                        <th class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">Invoice ID</th>
                                        <th class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">Method</th>
                                        <th class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">Amount Paid</th>
                                        <th class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">Type</th>
                                        <th class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">Reference No.</th>
                                        <th class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">Payment Date</th>
                                        <th class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">Status</th>
                                        <th class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">Notes</th>
                                        <th class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">Verified At</th>
                                        <th class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">Uploaded Receipt</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-600 ">
                                    @foreach ($payments as $payment)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">{{ $loop->iteration }}</td>
                                            <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">{{ $payment->invoice->invoice_number }}</td>
                                            <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">{{ $payment->paymentMethod?->mode_of_payment_name ?? 'N/A' }}</td>
                                            <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500 leading-tight">
                                                <div class="font-semibold">₱{{ number_format($payment->amount_paid, 2) }}</div>
                                                @if ($payment->convenience_fee)
                                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 italic">(with ₱{{ number_format($payment->convenience_fee, 2) }} convenience fee)</div>
                                                @endif
                                            </td>
                                            <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">{{ ucfirst($payment->payment_type) }}</td>
                                            <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500 break-words max-w-[96px]">{{ $payment->payment_reference_number ?? 'N/A' }}</td>
                                            <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">{{ $payment->payment_date ?? 'N/A' }}</td>
                                            <td class="border px-4 py-2 dark:text-gray-200 dark:border-gray-500">
                                                <span class="inline-block py-1 px-2 rounded-full text-xs font-semibold {{ $payment->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-500' : '' }} {{ $payment->payment_status === 'failed' ? 'bg-red-100 text-red-500' : '' }} {{ $payment->payment_status === 'completed' ? 'bg-green-100 text-green-500' : '' }}">{{ ucfirst($payment->payment_status) }}</span>
                                            </td>
                                            <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">{{ $payment->notes ?? '-' }}</td>
                                            <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">{{ $payment->verified_at ?? 'To be verified' }}</td>
                                            <td class="border px-4 py-2 space-x-2 dark:text-gray-200 dark:border-gray-500">
                                                @if (!$payment->payment_screenshot)
                                                    @if ($payment->mode_of_payment === 'cash')
                                                        <span class="text-gray-500 italic dark:text-gray-200">Cash Payment (no receipt uploaded)</span>
                                                    @else
                                                        <span class="text-gray-500 italic dark:text-gray-200">Completed via secure online payment</span>
                                                    @endif
                                                @else
                                                    @if ($payment->payment_status === 'pending')
                                                        <a href="{{ route('admin.view-payment-receipt', ['payment' => $payment->id]) }}" class="inline-block bg-yellow-100 hover:bg-yellow-200 text-yellow-500 font-semibold text-center py-2 px-3 rounded text-xs">Verify Receipt</a>
                                                    @elseif ($payment->payment_status === 'completed' || $payment->payment_status === 'failed')
                                                        <a href="{{ route('admin.view-payment-receipt', ['payment' => $payment->id]) }}" class="inline-block py-1 px-2 rounded-md text-xs font-semibold bg-green-100 text-green-500 text-center hover:bg-green-200 hover:text-green-600">View Receipt</a>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-600 italic">No payments found for this invoice.</p>
                    @endif
                </div>
            </section>

            <!---------------------------- MODALS ---------------------------------------->

            <!-- Show Receipt Modal -->
            @if ($showReceiptModal && $receipt)
                <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
                    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xl mx-4 overflow-hidden dark:bg-gray-800">
                        <!-- Header -->
                        <div class="bg-green-50 flex justify-between items-center border-b border-gray-200 px-6 py-4 dark:bg-gray-900 dark:border-gray-700">
                            <h2 class="text-xl font-bold tracking-wide text-green-700 dark:text-green-300">
                                Acknowledgment Receipt
                            </h2>
                            <button wire:click="closeReceiptModal"
                                class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-200 text-gray-600 hover:bg-red-100 hover:text-red-600 transition duration-200 text-xl"
                                title="Close">
                                &times;
                            </button>
                        </div>

                        <!-- Session Alert -->
                        @if (session('message'))
                            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                                class="fixed top-4 left-1/2 transform -translate-x-1/2 px-4 py-2 rounded-lg shadow-md
                                {{ session('alert-type') === 'success' ? 'bg-green-600 text-white' : 'bg-red-600 text-white' }}">
                                {{ session('message') }}
                            </div>
                        @endif

                        <!-- Receipt Details -->
                        <div class="p-6 space-y-4 text-gray-700 text-sm">
                            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                                <div class="flex justify-between py-2">
                                    <span class="font-semibold text-gray-800 dark:text-gray-100">Receipt Number</span>
                                    <span class="font-semibold text-gray-900">{{ $receipt->receipt_number }}</span>
                                </div>

                                <div class="flex justify-between py-2">
                                    <span class="font-semibold text-gray-800 dark:text-gray-100">Receipt Date</span>
                                    <span class="text-gray-900">{{ $receipt->receipt_date->format('F d, Y') }}</span>
                                </div>

                                <div class="flex justify-between py-2">
                                    <span class="font-semibold text-gray-800 dark:text-gray-100">Invoice Number</span>
                                    <span class="text-gray-900">{{ $invoice->invoice_number }}</span>
                                </div>

                                <div class="flex justify-between py-2">
                                    <span class="font-semibold text-gray-800 dark:text-gray-100">Guest</span>
                                    <span class="text-gray-900">
                                        {{ $transaction->transactionUser->first_name ?? 'N/A' }}
                                        {{ $transaction->transactionUser->last_name ?? '' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Payment Summary Table -->
                            <div class="mt-6">
                                <h2 class="font-semibold text-gray-800 dark:text-gray-100 mb-2">Payment Summary:</h2>
                                <div class="border border-gray-300 rounded-lg overflow-hidden text-sm">
                                    <table class="w-full">
                                        <thead class="bg-gray-100 dark:bg-gray-700">
                                            <tr>
                                                <th class="text-left px-4 py-2 font-medium">Description</th>
                                                <th class="text-right px-4 py-2 font-medium">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="px-4 py-2">Amount Received</td>
                                                <td class="px-4 py-2 text-right font-semibold text-green-700">
                                                    ₱{{ number_format($receipt->amount_received, 2) }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Notes Section -->
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 border border-gray-200 dark:border-gray-600">
                                <span class="block font-medium text-gray-800 dark:text-gray-300 mb-1">Notes</span>
                                <p class="text-gray-600 dark:text-gray-400 italic text-sm">
                                    {{ $receipt->notes ?? 'None' }}
                                </p>
                            </div>

                            <!-- Footer Text -->
                            <div class="text-center text-xs text-gray-500 dark:text-gray-400">
                                <p>This receipt acknowledges payment for the specified invoice.</p>
                                <p class="mt-1">Generated on {{ $receipt->created_at->format('F d, Y h:i A') }}</p>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-between gap-3 px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                            <!-- Print Receipt -->
                            <x-button wire:click="printOfficialReceipt" wire:loading.attr="disabled">
                                <div class="flex items-center">
                                    <span wire:loading wire:target="printOfficialReceipt" class="mr-2">
                                        <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z" />
                                        </svg>
                                    </span>
                                    <i class="fas fa-print mr-2" wire:loading.remove wire:target="printOfficialReceipt"></i>
                                    <span wire:loading.remove wire:target="printOfficialReceipt">Print Receipt</span>
                                </div>
                            </x-button>

                            <!-- Send Email -->
                            <x-warning-button wire:click="sendReceiptToEmail" wire:loading.attr="disabled">
                                <div class="flex items-center">
                                    <span wire:loading wire:target="sendReceiptToEmail" class="mr-2">
                                        <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z" />
                                        </svg>
                                    </span>
                                    <i class="fas fa-envelope mr-2" wire:loading.remove wire:target="sendReceiptToEmail"></i>
                                    <span wire:loading.remove wire:target="sendReceiptToEmail">Send to Email</span>
                                </div>
                            </x-warning-button>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Cannot Generate Receipt Modal -->
            @if ($cannotGenerateReceiptModal)
                <div>
                    <x-dialog-modal wire:model.live="cannotGenerateReceiptModal" type="ghost">
                        <x-slot name="title">
                            {{ __('Cannot Perform Action') }}
                        </x-slot>

                        <x-slot name="content">
                            {{ __('Receipt cannot be generated. Invoice still has balance due.') }}
                        </x-slot>

                        <x-slot name="footer">
                            <x-secondary-button wire:click="$set('cannotGenerateReceiptModal', false)"
                                wire:loading.attr="disabled">
                                {{ __('Cancel') }}
                            </x-secondary-button>
                        </x-slot>
                    </x-dialog-modal>
                </div>
            @endif

            <!-- Add Payment Modal -->
            @if ($createPaymentModal)
                <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto dark:bg-gray-800 dark:text-gray-200">

                        <div class="relative -mt-6 -mx-6 mb-6 bg-green-50 text-green-700 py-4 px-6 rounded-t-lg shadow-sm border-b dark:bg-gray-700 dark:text-green-300">
                            <h2 class="text-2xl font-bold text-center">Add Payment</h2>
                            <button wire:click="CloseCreatePaymentModal"
                                class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none">
                                <span class="-translate-y-[2px]">&times;</span>
                            </button>
                        </div>

                        <!-- Modal Content -->
                        <div>
                            <!-- Amount Paid -->
                            <div class="mt-4">
                                <label class="block text-sm text-gray-700 font-semibold dark:text-gray-300">Amount Paid <span class="text-red-500">*</span></label>
                                <input type="number" wire:model="amount_paid" placeholder="Ex. 1,200.00"
                                    class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    required>
                                @error('amount_paid')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Payment Date -->
                            <div class="mt-4">
                                <label class="block text-sm text-gray-700 font-semibold dark:text-gray-300">Payment Date <span class="text-red-500">*</span></label>
                                <input type="date" wire:model="payment_date"
                                    class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    required>
                                @error('payment_date')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Payment Type -->
                            <div class="mt-4">
                                <label class="block text-sm text-gray-700 font-semibold dark:text-gray-300">Payment Type <span class="text-red-500">*</span></label>
                                <select wire:model="payment_type"
                                    class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    required>
                                    <option value="">Select Payment Type</option>
                                                <option value="Room Rent">Room Rent</option>
                                                <option value="Security Deposit">Security Deposit</option>
                                                <option value="Remaining Balance">Remaining Balance</option>
                                                <option value="Merchandise">Merchandise</option>
                                                <option value="Accommodation Fully Paid">Accommodation Fully Paid</option>
                                                <option value="Accommodation Downpayment">Accommodation Downpayment</option>
                                                <option value="Accommodation Balance">Accommodation Balance</option>
                                </select>
                                @error('payment_type')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Payment Methods -->
                            <div class="mt-4">
                                <label for="payment_method_id" class="block text-sm text-gray-700 font-semibold dark:text-gray-300">Payment Method <span class="text-red-500">*</span></label>
                                <select wire:model="payment_method_id" id="payment_method_id"
                                    class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="">Select Payment Method</option>
                                    @foreach ($payment_methods as $payment_method)
                                        <option value="{{ $payment_method->id }}">
                                            {{ $payment_method->mode_of_payment_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('payment_method_id')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Upload Payment Screenshot -->
                            <div class="sm:col-span-2 mt-4">
                                <label for="payment_screenshot" class="block text-sm text-gray-700 font-semibold dark:text-gray-300 mb-2">Proof of Payment</label>

                                <!-- Hidden file input -->
                                <input id="payment_screenshot" type="file" accept="image/*" wire:model="payment_screenshot" class="hidden">

                                @if ($payment_screenshot && method_exists($payment_screenshot, 'temporaryUrl'))
                                    <!-- Show image preview -->
                                    <div class="relative w-full h-44 rounded-md shadow-sm overflow-hidden">
                                        <img src="{{ $payment_screenshot->temporaryUrl() }}" class="w-full h-full object-cover" alt="Payment Screenshot Preview"
                                            onclick="openModal('{{ $payment_screenshot->temporaryUrl() }}')">

                                        <label for="payment_screenshot"
                                            class="absolute top-1 right-1 bg-white text-gray-700 rounded-full px-1 text-xs cursor-pointer hover:bg-gray-200 hover:text-gray-800 transition">
                                            Re-Upload File
                                        </label>
                                    </div>
                                @else
                                    <!-- Show drag and drop box -->
                                    <label for="payment_screenshot">
                                        <div class="w-full px-4 py-8 border-2 border-dashed border-gray-300 text-center rounded-md text-gray-500 cursor-pointer hover:border-blue-400 dark:border-gray-600 dark:text-gray-400">
                                            <div class="mb-2">
                                                <i class="fas fa-upload mr-2"></i>
                                            </div>
                                            <p>Drag & drop a file or <span class="text-blue-500 underline">browse</span></p>
                                        </div>
                                    </label>
                                @endif

                                <!-- Loading Indicator -->
                                <div wire:loading wire:target="payment_screenshot" class="mt-2 text-gray-600 flex items-center dark:text-gray-400">
                                    <svg class="animate-spin h-5 w-5 mr-2 text-green-700" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                                        </circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                                        </path>
                                    </svg>
                                    <span>Uploading...</span>
                                </div>

                                <!-- Error Message -->
                                @error('payment_screenshot')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Notes -->
                            <div class="mt-4">
                                <label class="block text-sm text-gray-700 font-semibold dark:text-gray-300">Notes</label>
                                <input type="text" wire:model="notes" placeholder="Optionally add description of payment"
                                    class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                @error('notes')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="flex justify-between gap-3 mt-6">
                            <x-ghost-button wire:click="CloseCreatePaymentModal" class="dark:bg-gray-700 dark:text-gray-300">
                                Cancel
                            </x-ghost-button>
                            <x-button wire:click="CreatePayment">
                                Save Changes
                            </x-button>
                        </div>
                    </div>
                </div>

                <!-- Image Modal Script -->
                <script>
                    function openModal(src) {
                        document.getElementById('modalImg').src = src;
                        document.getElementById('imageModal').classList.remove('hidden');
                    }

                    function closeModal() {
                        document.getElementById('imageModal').classList.add('hidden');
                    }
                </script>

                <!-- Image Popup View -->
                <div id="imageModal" class="fixed z-50 inset-0 overflow-y-auto bg-black bg-opacity-80 hidden">
                    <div class="flex items-center justify-center min-h-screen">
                        <div class="relative modal-content">
                            <img id="modalImg" src="" class="max-w-full max-h-[80vh] rounded-md">
                            <button type="button" onclick="closeModal()"
                                class="absolute top-2 right-2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none">
                                <span class="leading-none translate-y-[-3px]">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Loading Modal -->
            <div wire:loading.flex class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white rounded-lg shadow-xl p-6 max-w-sm w-full mx-4 dark:bg-gray-800">
                    <div class="flex flex-col items-center">
                        <svg class="animate-spin h-12 w-12 text-green-600 mb-4" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z"></path>
                        </svg>
                        <p class="text-gray-700 dark:text-gray-300 text-lg font-semibold">Processing...</p>
                        <p class="text-gray-500 dark:text-gray-400 text-sm mt-2">Please wait</p>
                    </div>
                </div>
            </div>

            <!-- Manual PWD/Senior Discount Modal -->
            @if ($showDiscountModal)
                <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
                    <div class="bg-white p-6 rounded-lg shadow-lg w-[90%] md:w-[500px] max-h-[90vh] overflow-y-auto dark:bg-gray-800">
                        <!-- Header -->
                        <div class="relative -mt-6 -mx-6 mb-6 bg-green-50 text-green-700 py-4 px-6 rounded-t-lg shadow-sm border-b dark:bg-gray-700 dark:text-green-300">
                            <h2 class="text-2xl font-bold text-center">Apply PWD/Senior Discount</h2>
                            <button wire:click="closeDiscountModal"
                                class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none">
                                <span class="-translate-y-[2px]">&times;</span>
                            </button>
                        </div>

                        <!-- Simple Amount Input -->
                        <div class="space-y-4">
                            <div>
                                <label class="block font-medium mb-1 dark:text-gray-300">Total PWD/Senior Discount Amount <span class="text-red-500">*</span></label>
                                <input type="number"
                                    wire:model="manualDiscountAmount"
                                    min="0"
                                    max="{{ $this->computeBaseSubtotal() }}"
                                    step="0.01"
                                    class="w-full border rounded-lg px-3 py-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="0.00">
                                <p class="text-xs text-gray-500 mt-1 dark:text-gray-400">
                                    Maximum allowed: ₱{{ number_format($this->computeBaseSubtotal(), 2) }}
                                </p>
                                @error('manualDiscountAmount')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-between mt-6">
                            <x-ghost-button wire:click="closeDiscountModal" class="dark:bg-gray-700 dark:text-gray-300">
                                Cancel
                            </x-ghost-button>
                            <x-button wire:click="applyDiscounts">
                                Apply Discount
                            </x-button>
                        </div>
                    </div>
                </div>
            @endif

            <!-------------------------- END OF MODALS ---------------------------------->
            <!-- Back Button -->
            <div class="justify-end flex">
                <x-button onclick="history.back()" icon="fas fa-arrow-left" class="!bg-gray-200 !text-black hover:!bg-gray-300 focus:!ring-2 focus:!ring-gray-400 focus:!outline-none">
                    Back
                </x-button>
            </div>
        </div>
    </div>
</div>
