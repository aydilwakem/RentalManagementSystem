<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
            {{ __('View Reservation') }}
        </h2>
    </x-slot>


    <div class="py-1">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <x-button wire:click="exportReservationDetails">
                <!-- Spinner -->
                <span wire:loading wire:target="exportReservationDetails" class="mr-2">
                    <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                        </path>
                    </svg>
                </span>

                <i class="fas fa-file mr-2" wire:loading.remove wire:target="exportReservationDetails"></i>

                <!-- Button Text -->
                <span wire:loading.remove wire:target="exportReservationDetails">
                    Export PDF
                </span>

            </x-button>
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

            <!---------------------------- ADDITIONAL GUESTS DETAILS ---------------------------->
            <div class="bg-white shadow-lg rounded-lg border border-gray-200 p-6 dark:bg-gray-700 dark:border-gray-600">
                <h2 class="font-semibold text-xl text-green-700 leading-tight mb-4 dark:text-green-300">
                    {{ __('Additional Guests Details') }}
                </h2>
                @if ($guestDetails->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse border border-gray-300 text-sm text-left">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">Full Name</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">Gender</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">Residency</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">Country of Origin</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-600">
                                @foreach ($guestDetails as $guestDetail)
                                    <tr>
                                        <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                            {{ $guestDetail->first_name }}
                                            {{ $guestDetail->middle_name }}
                                            {{ $guestDetail->last_name }}
                                            {{ $guestDetail->suffix }}
                                        </td>
                                        <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                            {{ ucfirst($guestDetail->gender) ?? 'N/A' }}
                                        </td>
                                        <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                            {{ ucfirst($guestDetail->residency) }}</td>
                                        <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                            {{ ucfirst($guestDetail->country_of_origin) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-600 italic dark:text-gray-200">No additional guests found for this transaction.</p>
                @endif
            </div>

            <!---------------------------- TRANSACTION DETAILS --------------------------------->
            <div class="bg-white shadow-lg rounded-lg border border-gray-200 p-6 dark:bg-gray-700 dark:border-gray-600">
                <h2 class="font-semibold text-xl text-green-700 leading-tight mb-4 dark:text-green-300">
                    {{ __('Transaction Details') }}
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-gray-700 dark:text-gray-200">
                    <div class="col-span-full">
                        <strong>Transaction Status:</strong>
                        <div class="mt-1">
                            @if ($transaction->transaction_status === 'pending')
                                <span
                                    class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-gray-100 text-gray-600">Awaiting
                                    Payment</span>
                            @elseif ($transaction->transaction_status === 'reserved')
                                <span
                                    class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-blue-100 text-blue-500">Pending
                                    Verification</span>
                            @elseif ($transaction->transaction_status === 'receipt_verified')
                                <span
                                    class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-cyan-100 text-cyan-500">Payment
                                    Verified</span>
                            @elseif ($transaction->transaction_status === 'confirmed')
                                <span
                                    class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-emerald-100 text-emerald-600">Confirmed</span>
                            @elseif ($transaction->transaction_status === 'ongoing')
                                <span
                                    class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-600">On-Going
                                </span>
                            @elseif ($transaction->transaction_status === 'done')
                                <span
                                    class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-indigo-100 text-indigo-600">Completed</span>
                            @elseif ($transaction->transaction_status === 'no_show')
                                <span
                                    class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-pink-100 text-pink-500">No
                                    Show</span>
                            @elseif ($transaction->transaction_status === 'terminated')
                                <span
                                    class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-rose-100 text-rose-600">Terminated</span>
                            @elseif ($transaction->transaction_status === 'expired')
                                <span
                                    class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-orange-100 text-orange-500">Expired</span>
                            @elseif ($transaction->transaction_status === 'cancelled')
                                <span
                                    class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-red-100 text-red-600">Cancelled</span>
                            @else
                                {{ ucfirst($transaction->transaction_status) }}
                            @endif
                        </div>
                    </div>
                    <div>
                        <strong>Transaction ID:</strong>
                        <div>{{ $transaction->transaction_number }}</div>
                    </div>
                    <div>
                        <strong>Reservation Created At:</strong>
                        <div>{{ $transaction->created_at }}</div>
                    </div>
                    <div>
                        <strong>Reservation Source:</strong>
                        <div>{{ $transaction->reservation_source }}</div>
                    </div>
                    <div>
                        <strong>Check-in Date:</strong>
                        <div>{{ \Carbon\Carbon::parse($transaction->start_datetime)->format('F j, Y') }}</div>
                    </div>
                    <div>
                        <strong>Check-out Date:</strong>
                        <div>{{ \Carbon\Carbon::parse($transaction->end_datetime)->format('F j, Y') }}</div>
                    </div>
                    <div>
                        <strong>Duration of Stay:</strong>
                        <div>{{ $transaction->properties->first()?->pivot->days ?? 'N/A' }} day(s)</div>
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
                        <strong>Total Guests:</strong>
                        <div>{{ $transaction->pax }}</div>
                    </div>

                    <div>
                        <strong>Subtotal:</strong>
                        <div>₱{{ number_format($transaction->sub_total, 2) }}</div>
                    </div>

                    <div>
                        <strong>Convenience Fee (3%):</strong>
                        <div>₱{{ number_format($transaction->convenience_fee, 2) }}</div>
                    </div>

                    <div>
                        <strong>Promo Applied:</strong>
                        <div>
                            {{ $transaction->promoCode->code ?? '' }}

                            @if ($transaction->promoCode && $transaction->promoCode->discount_type == 'percentage')
                              ({{ number_format($transaction->promoCode->discount_value, 0) }}%)
                            @elseif ($transaction->promoCode)
                                {{-- Flat discount --}}
                                (₱{{ number_format($transaction->promoCode->discount_value, 2) }})
                            @endif

                            - ₱{{ number_format($transaction->promo_discount_amount, 2) }}
                        </div>
                    </div>

                    <div>
                        <strong>Total Amount:</strong>
                        <div>₱{{ number_format($transaction->total_amount, 2) }}</div>
                    </div>
                    <div>
                        <strong>Required Deposit:</strong>
                        <div>₱{{ number_format($transaction->deposit_amount, 2) }}</div>
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

            <!---------------------------- ROOM DETAILS ---------------------------------------->
            <div class="bg-white shadow-lg rounded-lg border border-gray-200 p-6 dark:bg-gray-700 dark:border-gray-600">
                <h2 class="font-semibold text-xl text-green-700 leading-tight mb-4 dark:text-green-300">
                    {{ __('Room Details') }}
                </h2>
                @if ($properties->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse border border-gray-300 text-sm text-left">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">Room</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">Category</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">No. of Adults
                                    </th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">No. of Kids</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">Stay Duration
                                    </th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">Extra Guests</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 text-right dark:text-gray-200 dark:border-gray-500">Rate</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 text-right dark:text-gray-200 dark:border-gray-500">Extra Guest Charge
                                    </th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 text-right dark:text-gray-200 dark:border-gray-500">Room Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-600">
                                @foreach ($properties as $property)
                                    <tr>
                                        <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">{{ $property->name_number }}</td>
                                       <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                            {{ optional($property->category)->name ?? 'N/A' }}
                                        </td>
                                        <td class="border px-4 py-2 text-gray-700 text-center dark:text-gray-200 dark:border-gray-500">
                                            {{ $property->pivot->adults ?? 'N/A' }}</td>
                                        <td class="border px-4 py-2 text-gray-700 text-center dark:text-gray-200 dark:border-gray-500">
                                            {{ $property->pivot->kids ?? 'N/A' }}</td>
                                        <td class="border px-4 py-2 text-gray-700 text-center dark:text-gray-200 dark:border-gray-500">
                                            {{ $property->pivot->days ?? 'N/A' }} day(s)</td>
                                        <td class="border px-4 py-2 text-gray-700 text-center dark:text-gray-200 dark:border-gray-500">
                                            {{ $property->pivot->extra_guest ?? 'N/A' }}</td>
                                        <td class="border px-4 py-2 text-gray-700 text-right dark:text-gray-200 dark:border-gray-500">
                                            ₱{{ number_format($property->pivot->amount ?? 0, 2) }}</td>
                                        <td class="border px-4 py-2 text-gray-700 text-right dark:text-gray-200 dark:border-gray-500">
                                            ₱{{ number_format($property->pivot->extra_charge ?? 0, 2) }}</td>
                                        <td class="border px-4 py-2 text-gray-700 text-right font-semibold dark:text-gray-200 dark:border-gray-500">
                                            ₱{{ number_format($property->pivot->total_amount ?? 0, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="text-right font-semibold text-base mt-2 text-gray-700">
                            Total Room Charges: ₱{{ number_format($totalRooms, 2) }}
                        </div>
                    </div>
                @else
                    <p class="text-gray-600 italic">No properties found for this transaction.</p>
                @endif
            </div>

            <!---------------------------- ADD ON SERVICES ------------------------------------>
            <div class="bg-white shadow-lg rounded-lg border border-gray-200 p-6 dark:bg-gray-700 dark:border-gray-600">
                <h2 class="font-semibold text-xl text-green-700 leading-tight mb-4 dark:text-green-300">
                    {{ __('Add-on Services/Activities') }}
                </h2>
                @if ($activities->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse border border-gray-300 text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th class="border px-4 py-2 font-medium text-gray-900 text-left dark:text-gray-200 dark:border-gray-500">Activity Name</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">Quantity</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">Unit Cost</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 text-right dark:text-gray-200 dark:border-gray-500">Activity Total
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-600">
                                @foreach ($activities as $activity)
                                    <tr>
                                        <td class="border px-4 py-2 text-gray-700 text-left dark:text-gray-200 dark:border-gray-500">
                                            {{ $activity->name }}</td>
                                        <td class="border px-4 py-2 text-gray-700 text-center dark:text-gray-200 dark:border-gray-500">
                                            {{ $activity->pivot->quantity ?? 'NA' }}</td>
                                        <td class="border px-4 py-2 text-gray-700 text-center dark:text-gray-200 dark:border-gray-500">
                                            ₱{{ number_format($activity->amount, 2) }}</td>
                                        <td class="border px-4 py-2 text-gray-700 text-right font-semibold dark:text-gray-200 dark:border-gray-500">
                                            ₱{{ $activity->pivot && $activity->pivot->quantity !== null
                                                ? number_format($activity->amount * $activity->pivot->quantity, 2)
                                                : 'NA' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="text-right font-semibold text-base mt-2 text-gray-700">
                            Total Activity Charges: ₱{{ number_format($totalAddons, 2) }}
                        </div>
                    </div>
                @else
                    <p class="text-gray-600 italic">No activities found for this transaction.</p>
                @endif
            </div>

            <!---------------------------- INVOICE DETAILS ------------------------------------->
            <div class="bg-white shadow-lg rounded-lg border border-gray-200 p-6 dark:bg-gray-700 dark:border-gray-600">
                <h2 class="font-semibold text-xl text-green-700 leading-tight mb-4 dark:text-green-300">
                    {{ __('Invoice Details') }}
                </h2>

            @if ($invoice)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-gray-700 dark:text-gray-200">
                        <!-- Invoice Info -->
                        <div><strong>Invoice ID:</strong></div>
                        <div>{{ $invoice->invoice_number }}</div>

                        <div><strong>Due Date:</strong></div>
                        <div>
                            {{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('F j, Y') : 'Not yet set' }}
                        </div>

                        <!-- COst Summary -->
                        <div><strong>Grand Total:</strong></div>
                        <div class="font-semibold">₱{{ number_format($this->invoice->sub_total, 2) }}</div>

                        <div><strong>Required Deposit:</strong></div>
                        <div>₱{{ number_format($transaction->deposit_amount, 2) }}</div>

                        <div><strong>Amount Paid:</strong></div>
                        <div>₱{{ number_format($invoice->amount_paid, 2) }}</div>

                        <div><strong>Balance Due:</strong></div>
                        <div>₱{{ number_format($this->invoice->balance_due, 2) }}</div>

                        <!-- Payment Status -->
                        <div><strong>Invoice Status:</strong></div>
                        <div>
                            @if ($invoice->invoice_status === 'pending')
                                <span
                                    class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-600">
                                    Pending
                                </span>
                            @elseif ($invoice->invoice_status === 'complete' || $invoice->invoice_status === 'completed')
                                <span
                                    class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-green-100 text-green-600">
                                    Completed
                                </span>
                            @elseif ($invoice->invoice_status === 'failed')
                                <span
                                    class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-red-100 text-red-600">
                                    Failed
                                </span>
                            @elseif ($invoice->invoice_status === 'overdue')
                                <span
                                    class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-orange-100 text-orange-600">
                                    Overdue
                                </span>
                            @endif
                        </div>


                        <!-- Timeline -->
                        <div><strong>Completed At:</strong></div>
                        <div>
                            {{ $invoice->completed_at ? \Carbon\Carbon::parse($invoice->completed_at)->format('F j, Y') : 'Not yet completed' }}
                        </div>


                    </div>

                     <!-- Items Table -->
                    <div class="overflow-x-auto mt-4">
                        <table class="min-w-full border-collapse border border-gray-300 text-sm text-left">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">#</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">Item & Description</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">Qty</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">Days</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">Unit Cost</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">Amount</th>
                                     <th class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">Timestamp</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">Status</th>                  
                                    <th class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white text-gray-700 dark:bg-gray-600 dark:text-gray-200">

                                {{-- Counter --}}
                                @php $rowNumber = 1; @endphp

                                {{-- Loop through Rooms --}}
                                @foreach ($properties as $property)
                                    @php
                                        $hasExtraGuest = $property->pivot->extra_guest > 0;
                                    @endphp

                                    <tr>
                                        {{-- Use rowspan if there's an extra guest --}}
                                        <td class="border px-4 py-2 dark:border-gray-500" @if($hasExtraGuest) rowspan="2" @endif>
                                            {{ $rowNumber++ }}
                                        </td>
                                        <td class="border px-4 py-2 dark:border-gray-500">Room – {{ $property->name_number }}</td>
                                        <td class="border px-4 py-2 text-center dark:border-gray-500">1</td>
                                        <td class="border px-4 py-2 text-center dark:border-gray-500">{{ $property->pivot->days }}</td>
                                        <td class="border px-4 py-2 text-center dark:border-gray-500">
                                            ₱{{ number_format($property->amount, 2) }}
                                        </td>
                                        <td class="border px-4 py-2 text-center dark:border-gray-500">
                                            ₱{{ number_format($property->pivot->amount, 2) }}
                                        </td>
                                       
                                        <td class="border px-4 py-2 text-center dark:border-gray-500">
                                            <span class="" title="{{ $property->pivot->created_at->format('F j, Y - g:i A') }}">
                                                {{ $property->pivot->created_at->diffForHumans() }}
                                            </span>
                                        </td>
                                          <td class="border px-4 py-2 text-center dark:border-gray-500">
                                           {{ $property->pivot->payment_status }}
                                        </td>
                                            
                                       @if($property->pivot->payment_status !== 'paid')
                                            {{-- Editable: Show Action Buttons --}}
                                            <td class="border px-4 py-2 text-center space-x-2 dark:border-gray-500">
                                                {{-- Edit Button --}}
                                                <button wire:click="editProperty({{ $property->id }})" class="text-yellow-600 hover:text-yellow-700 dark:text-yellow-400 dark:hover:text-yellow-500" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
                                        @else
                                            {{-- Not Editable: Show Text or Icon --}}
                                            <td class="border px-4 py-2 text-center text-gray-400 italic dark:border-gray-500">
                                                <i class="fas fa-lock mr-1"></i>
                                            </td>
                                        @endif

                                    </tr>

                                    {{-- Only show if there are extra guests --}}
                                    @if ($hasExtraGuest)
                                        <tr>
                                            <td class="border px-4 py-2">Extra Guest</td>
                                            <td class="border px-4 py-2 text-center">{{ $property->pivot->extra_guest }}</td>
                                            <td class="border px-4 py-2 text-center">{{ $property->pivot->days }}</td>
                                            <td class="border px-4 py-2 text-center">
                                                ₱{{ number_format($property->extra_person_charge, 2) }}
                                            </td>
                                            <td class="border px-4 py-2 text-center">
                                                ₱{{ number_format($property->pivot->extra_charge, 2) }}
                                            </td>
                                            <td class="border px-4 py-2 text-center dark:border-gray-500">
                                            <span class="" title="{{ $property->pivot->created_at->format('F j, Y - g:i A') }}">
                                                {{ $property->pivot->created_at->diffForHumans() }}
                                            </span>
                                        </td>
                                          <td class="border px-4 py-2 text-center dark:border-gray-500">
                                          {{ $property->pivot->payment_status }}
                                        </td>
                                         
                                       @if($property->pivot->payment_status !== 'paid')
                                            {{-- Editable: Show Action Buttons --}}
                                            <td class="border px-4 py-2 text-center space-x-2 dark:border-gray-500">
                                                {{-- Edit Button --}}
                                                <button wire:click="" class="text-yellow-600 hover:text-yellow-700 dark:text-yellow-400 dark:hover:text-yellow-500" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
                                        @else
                                            {{-- Not Editable: Show Text or Icon --}}
                                            <td class="border px-4 py-2 text-center text-gray-400 italic dark:border-gray-500">
                                                <i class="fas fa-lock mr-1"></i>
                                            </td>
                                        @endif

                                        </tr>
                                    @endif
                                @endforeach


                                {{-- Loop through Add-on Services --}}
                                @foreach ($activities as $activity)
                                    <tr>
                                        <td class="border px-4 py-2 dark:border-gray-500">{{ $rowNumber++ }}</td>
                                        <td class="border px-4 py-2 dark:border-gray-500">Activity - {{ $activity->name }}</td>
                                        <td class="border px-4 py-2 text-center dark:border-gray-500">{{ $activity->pivot->quantity }}</td>
                                        <td class="border px-4 py-2 text-center dark:border-gray-500"></td>
                                        <td class="border px-4 py-2 text-center dark:border-gray-500">₱{{ number_format($activity->amount, 2) }}</td>
                                        <td class="border px-4 py-2 text-center dark:border-gray-500">
                                            ₱{{ number_format($activity->amount * $activity->pivot->quantity, 2) }}
                                        </td>
                                       <td class="border px-4 py-2 text-center dark:border-gray-500">
                                            <span class="" title="{{ $activity->pivot->created_at->format('F j, Y - g:i A') }}">
                                                {{ $activity->pivot->created_at->diffForHumans() }}
                                            </span>
                                        </td>
                                          <td class="border px-4 py-2 text-center dark:border-gray-500">
                                           {{ $activity->pivot->payment_status }}
                                        </td>
                                           
                                         @if($activity->pivot->payment_status !== 'paid')

                                            {{-- Editable: Show Action Buttons --}}
                                            <td class="border px-4 py-2 text-center space-x-2 dark:border-gray-500">
                                            {{-- Edit Button --}}
                                           <button wire:click="editActivity({{ $activity->pivot->id }})"
                                                    class="text-yellow-600 hover:text-yellow-700 dark:text-yellow-400 dark:hover:text-yellow-500"
                                                    title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            {{-- Delete Button --}}
                                            <button wire:click="deleteActivity({{ $activity->pivot->id }})" class="text-red-600 hover:text-red-700 dark:text-red-500 dark:hover:text-red-600" title="Delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                            </td>
                                        @else
                                            {{-- Not Editable: Show Text or Icon --}}
                                            <td class="border px-4 py-2 text-center text-gray-400 italic dark:border-gray-500">
                                                <i class="fas fa-lock mr-1"></i>
                                            </td>
                                        @endif

                                    </tr>
                                @endforeach

                               

                            </tbody>
                        </table>

                         <!-- Sub Total -->
                        <div class="text-right font-semibold text-base mt-2 text-gray-700">
                            Subtotal: ₱{{ number_format($this->computeBaseSubtotal(), 2) }}
                        </div>

                         <!-- Convenience Fee -->
                        <div class="text-right font-semibold text-base mt-2 text-gray-700">
                            Convenience Fee: ₱{{ number_format($this->computeConvenienceFeeTotal(), 2) }}
                        </div>

                        <!-- Grand Total -->
                        <div class="text-right font-semibold text-base mt-2 text-gray-700">
                            Grand Total: ₱{{ number_format($this->invoice->sub_total, 2) }}
                        </div>

                         <!-- Amount Paid -->
                        <div class="text-right font-semibold text-base mt-2 text-gray-700">
                            Amount Paid: ₱{{ number_format($this->invoice->amount_paid, 2) }}
                        </div>

                         <!-- Balance Due -->
                        <div class="text-right font-semibold text-base mt-2 text-gray-700">
                            Balance Due: ₱{{ number_format($this->invoice->balance_due, 2) }}
                        </div>
                    </div>

                     {{-- Add Item Button Row --}}
                     
                                <tr>
                                    <td colspan="7" class="border px-4 py-2 text-center dark:border-gray-500">
                                         <x-button wire:click="openModal('activity')" icon="fas fa-plus">
                                            Add Activity
                                        </x-button>
                                    </td>
                                </tr>

                                    <tr>
                                    <td colspan="7" class="border px-4 py-2 text-center dark:border-gray-500">
                                         <x-button wire:click="openModal('service')" icon="fas fa-plus">
                                            Add Services
                                        </x-button>
                                    </td>
                                </tr>
     

                                <tr>
                                    <td colspan="7" class="border px-4 py-2 text-center dark:border-gray-500">
                                         <x-button wire:click="OpenCreatePaymentModal" icon="fas fa-plus">
                                            Add Other Charges
                                        </x-button>
                                    </td>
                                </tr>

                            


                    <!------------------------  REQUEST REMAINING BALANCE ------------------------------------->
                    @if ($invoice->balance_due > 0 && !$invoice->requested_remaining_balance)
                        <x-button wire:click="requestRemainingBalance" wire:loading.attr="disabled" class="mt-6">
                            <div class="flex items-center justify-center">
                                <!-- Spinner -->
                                <span wire:loading class="mr-2" wire:target="requestRemainingBalance">
                                    <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                                        </path>
                                    </svg>
                                </span>

                                <i class="fas fa-money-bill-wave mr-2" wire:loading.remove
                                    wire:target="requestRemainingBalance"></i>

                                <span wire:loading.remove wire:target="requestRemainingBalance">
                                    Request Remaining Balance
                                </span>
                            </div>
                        </x-button>
                    @elseif ($invoice->balance_due > 0 && $invoice->requested_remaining_balance)
                        <p class="text-gray-500 italic">Waiting for guest to pay remaining balance...</p>
                    @endif

            </div>

            @else
            <p class="text-gray-600 italic">No invoice found for this transaction.</p>
            @endif




            <!------------------------  GENERATE OFFICIAL RECEIPT ------------------------------------->
            @if ($transaction->transaction_status == 'done')
                <div>
                    @if (is_null($transaction->invoice->receipt))
                        <!-- Show this if receipt does NOT exist -->
                        <x-button wire:click="GenerateReceipt" wire:loading.attr="disabled"
                            wire:target="GenerateReceipt"
                            class=" !bg-blue-600 text-white rounded hover:!bg-blue-700 focus:ring-2 focus:!ring-blue-600 focus:!border-blue-600 transition items-center gap-2">

                            <!-- Show spinner and text while loading -->
                            <span wire:loading wire:target="GenerateReceipt" class=" items-center gap-2">
                                <span>Generating...</span>
                            </span>

                            <!-- Show default text when not loading -->
                            <span wire:loading.remove wire:target="GenerateReceipt">
                                <i class="fas fa-receipt"></i>
                                Generate Official Receipt
                            </span>
                        </x-button>
                    @else
                        <!-- Show this if receipt already exists -->
                        <x-button wire:click="ShowReceipt" icon="fas fa-eye">
                            View Receipt
                        </x-button>
                    @endif
                </div>
            @endif


            @if ($showReceiptModal && $receipt)
                <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                    <div class="bg-white rounded-lg shadow-xl w-full max-w-lg mx-4">
                        <!-- Header -->
                        <div class="flex justify-between items-center border-b border-gray-200 px-6 py-4">
                            <h2 class="text-2xl font-semibold text-gray-800">Official Receipt</h2>
                            <button wire:click="$set('showReceiptModal', false)"
                                class="flex items-center justify-center w-7 h-7 rounded-full bg-gray-200 text-gray-600 hover:bg-red-100 hover:text-red-600 transition duration-200 text-2xl ">
                                <span class="leading-none translate-y-[-3px]">&times;</span>
                            </button>

                        </div>

                        {{-- Display Session Message --}}
                        @if (session('message'))
                            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                                class="fixed top-4 left-1/2 transform -translate-x-1/2 px-4 py-2 rounded-lg shadow-lg
                {{ session('alert-type') === 'success' ? 'bg-red-500 text-white' : 'bg-green-500 text-white' }}">
                                {{ session('message') }}
                            </div>
                        @endif

                        <!-- Content -->
                        <div class="px-6 py-5 space-y-4 text-gray-700 text-sm">
                            <div class="flex justify-between">
                                <span class="font-semibold">Receipt Number:</span>
                                <span class="text-gray-900">{{ $receipt->receipt_number }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-semibold">Receipt Date:</span>
                                <span class="text-gray-900">{{ $receipt->receipt_date->format('F d, Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-semibold">Invoice Number:</span>
                                <span class="text-gray-900">{{ $invoice->invoice_number }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-semibold">Guest:</span>
                                <span class="text-gray-900">
                                    {{ $transaction->transactionUser->first_name ?? 'N/A' }}
                                    {{ $transaction->transactionUser->last_name ?? '' }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-semibold">Amount Received:</span>
                                <span
                                    class="text-green-600 font-semibold">₱{{ number_format($receipt->amount_received, 2) }}</span>
                            </div>
                            <div>
                                <span class="font-semibold">Notes:</span>
                                <p class="mt-1 text-gray-600 italic">{{ $receipt->notes ?? 'None' }}</p>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-between gap-3 px-6 py-4 border-t border-gray-200">

                            <!-- Print Receipt Button -->
                            <x-button wire:click="printOfficialReceipt" wire:loading.attr="disabled">
                                <div class="flex items-center justify-center">
                                    <!-- Spinner -->
                                    <span wire:loading class="mr-2" wire:target="printOfficialReceipt">
                                        <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4">
                                            </circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                                            </path>
                                        </svg>
                                    </span>

                                    <i class="fas fa-print mr-2" wire:loading.remove
                                        wire:target="printOfficialReceipt"></i>

                                    <!-- Button Text -->
                                    <span wire:loading.remove wire:target="printOfficialReceipt">
                                        Print Receipt
                                    </span>
                                </div>
                            </x-button>

                            <!-- Send to Email Button -->
                            <x-warning-button wire:click="sendReceiptToEmail" wire:loading.attr="disabled">
                                <div class="flex items-center justify-center">
                                    <!-- Spinner -->
                                    <span wire:loading class="mr-2" wire:target="sendReceiptToEmail">
                                        <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4">
                                            </circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                                            </path>
                                        </svg>
                                    </span>

                                    <i class="fas fa-envelope mr-2" wire:loading.remove
                                        wire:target="sendReceiptToEmail"></i>

                                    <!-- Button Text -->
                                    <span wire:loading.remove wire:target="sendReceiptToEmail">
                                        Send to Email
                                    </span>
                                </div>
                            </x-warning-button>

                        </div>

                    </div>
                </div>
            @endif


            <!---------------------------- PAYMENT DETAILS ---------------------------------------->
            <section id="payments">
                <div class="bg-white shadow-lg rounded-lg border border-gray-200 p-6 dark:bg-gray-700 dark:border-gray-600">
                    <div class="justify-between flex items-center">
                        <h2 class="font-semibold text-xl text-green-700 leading-tight mb-4 dark:text-green-300">
                            Payments
                        </h2>
                        <div class="text-left mb-4 flex items-center gap-2">
                            <!-- Info Icon with Tooltip -->
                            <div class="relative group inline-block">
                                <i class="fas fa-info-circle text-gray-500 text-sm cursor-pointer dark:text-gray-200"></i>

                                <!-- Tooltip -->
                                <div
                                    class="absolute left-1/2 -translate-x-1/2 bottom-full mb-2 w-max max-w-xs text-sm text-white bg-gray-800 rounded px-2 py-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-10">
                                    Create Payment is for cash payments only.
                                </div>
                            </div>
                            <x-button wire:click="OpenCreatePaymentModal">
                                <i class="fas fa-plus mr-2"></i>
                                Create Payment
                            </x-button>
                        </div>
                    </div>
                    @if ($payments->isNotEmpty())
                        <div class="overflow-x-auto">
                            <table class="min-w-full border-collapse border border-gray-300 text-sm">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <th class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">Payment ID</th>
                                        <th class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">Invoice ID</th>
                                        <th class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">Method</th>
                                        <th class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">Amount Paid</th>
                                        <th class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">Convenience Fee</th>
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
                                            <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">{{ $payment->id }}</td>
                                            <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                                {{ $payment->invoice->invoice_number }}</td>
                                            <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                            {{ $payment->mode_of_payment ?? $payment->paymentMethod->mode_of_payment_name ?? 'N/A' }}
                                        </td>
                                            <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                                ₱{{ number_format($payment->amount_paid, 2) }}</td>
                                            <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                                ₱{{ number_format($payment->convenience_fee, 2) ?? 'N/A' }}</td>
                                            <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                                {{ ucfirst($payment->payment_type) }}</td>
                                            <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                                {{ $payment->payment_reference_number ?? 'N/A' }}</td>
                                            <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                                {{ $payment->payment_date ?? 'N/A' }}</td>
                                            <td class="border px-4 py-2 dark:text-gray-200 dark:border-gray-500">
                                                <span
                                                    class="inline-block py-1 px-2 rounded-full text-sm font-semibold
                                                {{ $payment->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-500' : '' }}
                                                {{ $payment->payment_status === 'failed' ? 'bg-red-100 text-red-500' : '' }}
                                                {{ $payment->payment_status === 'completed' ? 'bg-green-100 text-green-500' : '' }}">
                                                    {{ ucfirst($payment->payment_status) }}
                                                </span>
                                            </td>
                                            <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">{{ $payment->notes ?? '-' }}
                                            </td>
                                            <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                                {{ $payment->verified_at ?? 'To be verified' }}</td>
                                            <td class="border px-4 py-2 space-x-2 dark:text-gray-200 dark:border-gray-500">
                                                @if (!$payment->payment_screenshot)
                                                @if ($payment->mode_of_payment === 'cash')
                                                    <span class="text-gray-500 italic dark:text-gray-200">Cash payment (no receipt uploaded)</span>
                                                @else
                                                   <span class="text-gray-500 italic dark:text-gray-200">Paid via PayMongo (no screenshot required)</span>
                                                @endif
                                                @else
                                                    @if ($payment->payment_status === 'pending')
                                                        <a href="{{ route('admin.view-payment-receipt', ['payment' => $payment->id]) }}"
                                                            class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white font-semibold text-center py-2 px-4 rounded text-xs">
                                                            Verify Receipt
                                                        </a>
                                                    @elseif ($payment->payment_status === 'completed' || $payment->payment_status === 'failed')
                                                        <a href="{{ route('admin.view-payment-receipt', ['payment' => $payment->id]) }}"
                                                            class="inline-block py-1 px-2 rounded-md text-sm font-semibold bg-green-100 text-green-500 text-center hover:bg-green-200 hover:text-green-600">
                                                            View Receipt
                                                        </a>
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
            <div>
                @if ($cannotGenerateReceiptModal)
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
                @endif
            </div>

            <div>
                @if ($createPaymentModal)
                    <div id="guestModal"
                        class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
                        <div
                            class="bg-white p-6 rounded-lg shadow-lg w-[90%] md:w-[600px] max-h-[90vh] overflow-y-auto">
                            <h2 class="text-xl font-bold mb-4 text-green-700 text-center">Add Payment</h2>

                            <!-- Amount Paid -->
                            <div class="mt-4">
                                <label class="block text-sm text-gray-700 font-semibold">Amount Paid <span class="text-red-500">*</span></label>
                                <input type="number" wire:model="amount_paid"
                                    placeholder="Ex. 1,200.00"
                                    class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600" required>
                                @error('amount_paid')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Payment Date -->
                            <div class="mt-4">
                                <label class="block text-sm text-gray-700 font-semibold">Payment Date <span class="text-red-500">*</span></label>
                                <input type="date" wire:model="payment_date"
                                    class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600" required>
                                @error('payment_date')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Payment Type -->
                            <div class="mt-4">
                                <label class="block text-sm text-gray-700 font-semibold">Payment Type <span class="text-red-500">*</span></label>
                                <select wire:model="payment_type"
                                    class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600" required>
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
                                <label class="block text-sm text-gray-700 font-semibold">Notes</label>
                                <input type="text" wire:model="notes"
                                    placeholder="Optionally add description of payment"
                                    class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600">
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

                <!-- Service Modal -->
                @if ($activeModal === 'service')
                    <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
                        <div class="bg-white p-6 rounded-lg shadow-lg w-[90%] md:w-[600px] max-h-[90vh] overflow-y-auto">
                            <h2 class="text-xl font-bold mb-4 text-blue-700 text-center">Add Service</h2>

                            @foreach ($availableServices as $service)
                                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-200 mb-4 dark:bg-gray-700 dark:border-gray-600">
                                    <div class="p-4 flex flex-col gap-2">
                                        <h2 class="text-lg font-semibold text-gray-800 dark:text-white">{{ $service->name }}</h2>

                                        <p class="text-sm text-gray-600 dark:text-gray-300">
                                            {{ $service->description ?? 'No description provided.' }}
                                        </p>

                                        <div class="text-md font-semibold text-blue-600 dark:text-blue-300">
                                            ₱{{ number_format($service->amount, 2) }} <span class="text-sm text-gray-500">({{ $service->unit }})</span>
                                        </div>

                                        {{-- Quantity & Button --}}
                                        <div class="flex items-center justify-between mt-2 gap-4">

                                                <!-- Quantity Counter -->
                                                    <div class="flex flex-col">
                                                        <label for="quantity-{{ $service->id }}"
                                                            class="text-sm font-medium text-gray-700 mb-1">Quantity:</label>

                                                        <!-- Quantity Counter Buttons -->
                                                        <div class="flex items-center">
                                                              <button wire:click="decrementItemQuantity('service', {{ $service->id }})"
                                                                        class="px-2 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">-</button>

                                                                <span class="text-center w-16 py-1 bg-white border border-gray-300 rounded">
                                                                    {{ $quantity[$service->id] ?? 1 }}
                                                                </span>

                                                                <button wire:click="incrementItemQuantity('service', {{ $service->id }})"
                                                                        class="px-2 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">+</button>

                                                                <!-- Hidden input to bind the quantity -->
                                                                <input type="hidden" wire:model="quantity.{{ $service->id }}">     
                                                        </div>
                                                    </div>
                                        

                                            {{-- Add/Remove Button --}}
                                            @php
                                                $inCart = collect($cart)->contains(function ($item) use ($service) {
                                                    return $item['type'] === 'service' && $item['service_id'] == $service->id;
                                                });
                                            @endphp
                                            <button wire:click="{{ $inCart ? 'removeItemFromCart' : 'addItemToCart' }}('service', {{ $service->id }})"
                                                    class="px-4 py-2 {{ $inCart ? 'bg-red-600 hover:bg-red-700' : 'bg-blue-600 hover:bg-blue-700' }} text-white rounded text-sm font-semibold uppercase transition duration-150 ease-in-out">
                                                {{ $inCart ? 'Remove' : 'Add' }}   
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <!-- Actions -->
                            <div class="flex justify-between items-center gap-2 mt-6">
                                <x-button type="button" wire:click="closeModal"
                                    class="!bg-gray-200 !text-black hover:!bg-gray-300 focus:!ring-2 focus:!ring-gray-400 focus:!outline-none">
                                    Cancel
                                </x-button>
                                <x-button type="button" wire:click="saveServices">
                                    Save Changes
                                </x-button>
                            </div>
                        </div>
                    </div>
                @endif



                <!-- Activity Modal -->
                <div>
                @if ($activeModal === 'activity')
                    <div id="guestModal"
                        class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
                        <div
                            class="bg-white p-6 rounded-lg shadow-lg w-[90%] md:w-[600px] max-h-[90vh] overflow-y-auto">
                            <h2 class="text-xl font-bold mb-4 text-green-700 text-center">Add Activity</h2>

                      
                               @foreach ($availableActivities as $activity)
                                    <div
                                        class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-200 mb-0 flex flex-col md:flex-row
                                        dark:bg-gray-700 dark:border-gray-600">

                                        {{-- Image of the Activity --}}
                                        <div class="md:w-1/2">
                                            @if ($activity->image)
                                                <img class="w-full h-56      object-cover" src="{{ asset('storage/' . $activity->image) }}"
                                                    alt="{{ $activity->name }}">
                                            @else
                                                <img class="w-full h-56 object-cover" src="{{ asset('images/rms-default.png') }}"
                                                    alt="{{ $activity->name }}">
                                            @endif
                                        </div>

                                        <div class="md:w-1/2 p-4 flex flex-col justify-between">
                                            <div>
                                                {{-- Activity Name --}}
                                                <h2 class="text-xl font-semibold text-gray-800 dark:text-white"> {{ $activity->name }}</h2>
                                              
                                                {{-- Activity Description --}}
                                                <p class="text-gray-600 text-sm mb-2 text-justify dark:text-gray-200">
                                                    {{-- Show more / less when description is long --}}
                                                    @if (empty($activity->description))
                                                        <span class="italic text-gray-400">No description provided</span>
                                                    @elseif ($expandedActivity === $activity->id)
                                                        {{ $activity->description }}
                                                        <a href="#" wire:click.prevent="toggleActivityDescription({{ $activity->id }})"
                                                            class="text-gray-600 hover:underline ml-1 dark:text-gray-200">Show
                                                            less</a>
                                                    @else
                                                        {{ Str::limit($activity->description, 100, '...') }}
                                                        @if (Str::length($activity->description) > 100)
                                                            <a href="#"
                                                                wire:click.prevent="toggleActivityDescription({{ $activity->id }})"
                                                                class="text-gray-600 hover:underline ml-1 dark:text-gray-200">Show
                                                                more</a>
                                                        @endif
                                                    @endif
                                                </p>

                                                 {{-- Activity Amount --}}
                                                <div class="text-lg font-semibold text-green-600 dark:text-green-300">
                                                    @if ($activity->amount == 0)
                                                        <span class="text-green-600 font-semibold">FREE</span>
                                                    @else
                                                        ₱{{ number_format($activity->amount, 2) }}
                                                    @endif
                                                </div>
                                            </div>


                                            {{-------------- QUANTITY COUNTER AND ADD/REMOVE ACTIVITY BUTTONS ---------------}}
                                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">

                                               
                                                <div class="flex items-center gap-2">

                                                    <!-- Quantity Counter -->
                                                    <div class="flex flex-col">
                                                        <label for="quantity-{{ $activity->id }}"
                                                            class="text-sm font-medium text-gray-700 mb-1">Quantity:</label>

                                                            <!-- Quantity Counter -->
                                                            <div class="flex flex-col">
                                                                <label for="quantity-{{ $activity->id }}"
                                                                    class="text-sm font-medium text-gray-700 mb-1">Quantity:</label>

                                                                <!-- Quantity Counter Buttons -->
                                                                <div class="flex items-center">
                                                                    <button wire:click="decrementItemQuantity('activity', {{ $activity->id }})"
                                                                        class="px-2 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">-</button>

                                                                    <span class="text-center w-16 py-1 bg-white border border-gray-300 rounded">
                                                                        {{ $quantity[$activity->id] ?? 1 }}
                                                                    </span>

                                                                    <button wire:click="incrementItemQuantity('activity', {{ $activity->id }})"
                                                                        class="px-2 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">+</button>

                                                                    <!-- Hidden input to bind the quantity -->
                                                                    <input type="hidden" wire:model="quantity.{{ $activity->id }}">
                                                                </div>
                                                            </div>
                                                      
                                                    </div>


                                                    {{-- Cart Collection for Activity --}}
                                                    @php
                                                        $cartCollection = collect($cart); // Convert array to collection
                                                        $activityInCart = $cartCollection->contains(function ($item) use ($activity) {
                                                            return $item['type'] === 'activity' && $item['activity_id'] == $activity->id;
                                                        });
                                                    @endphp

                                                    {{-- Add and Remove buttons for Activity --}}
                                                    <button
                                                        wire:click="{{ $activityInCart ? 'removeItemFromCart' : 'addItemToCart' }}('activity', {{ $activity->id }})"
                                                        class="w-full px-4 py-2 {{ $activityInCart ? 'bg-red-600 hover:bg-red-700' : 'bg-green-700 hover:bg-green-800' }} text-white font-semibold rounded-md text-sm transition ease-in-out duration-150 uppercase"
                                                        wire:loading.attr="disabled"
                                                    >
                                                        <div class="flex items-center justify-center">
                                                            <!-- Spinner -->
                                                            <span
                                                                wire:loading
                                                                wire:target="{{ $activityInCart ? 'addItemToCart' : 'addItemToCart' }}('activity', {{ $activity->id }})"
                                                                class="mr-2"
                                                            >
                                                                <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                                                    <path class="opacity-75" fill="currentColor"
                                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z" />
                                                                </svg>
                                                            </span>

                                                            <!-- Button Text -->
                                                            <span
                                                                wire:loading.remove
                                                                wire:target="{{ $activityInCart ? 'addItemToCart' : 'addItemToCart' }}('activity', {{ $activity->id }})"
                                                            >
                                                                {{ $activityInCart ? 'Remove' : 'Add' }}
                                                            </span>
                                                        </div>
                                                    </button>

                                    



                                                </div>

                                            </div>


                                        </div>
                                    </div>
                                @endforeach


                            <!-- Actions -->
                            <div class="flex justify-between items-center gap-2 mt-6">
                                <x-button type="button" wire:click="closeModal"
                                    class="!bg-gray-200 !text-black hover:!bg-gray-300 focus:!ring-2 focus:!ring-gray-400 focus:!outline-none">
                                    Cancel
                                </x-button>
                                <x-button type="button" wire:click="saveActivity">
                                    Save Changes
                                </x-button>
                            </div>

                        </div>
                    </div>
                @endif
                </div>

                @if($showEditActivityModal)
                <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-md">
                        <h2 class="text-lg font-semibold mb-4">Edit Activity Quantity</h2>

                        <div class="mb-4">
                            <label class="block mb-1">Quantity</label>
                            <input type="number" wire:model="activityQuantity" min="1"
                                class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:border-gray-600">

                                   @error('activityQuantity')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                        </div>

                        <div class="flex justify-end space-x-2">
                            <button wire:click="$set('showEditActivityModal', false)"
                                class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">Cancel</button>
                            <button wire:click="updateActivity"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update</button>
                        </div>
                    </div>
                </div>
          
                @endif


           

            <!-------------------------- END OF MODALS ---------------------------------->


            <!-- Back Button -->
            <div class="justify-end flex">
                <x-button onclick="history.back()" icon="fas fa-arrow-left"
                    class="!bg-gray-200 !text-black hover:!bg-gray-300 focus:!ring-2 focus:!ring-gray-400 focus:!outline-none">
                    Back
                </x-button>
            </div>
        </div>
    </div>
</div>
</div>



{{-- <div class="bg-white p-6 rounded-lg shadow-md max-w-4xl mx-auto">
    <h2 class="text-2xl font-semibold mb-4">Invoice</h2>

    <div class="mb-4">
        <h3 class="text-lg font-semibold">Accommodations</h3>
        <p><strong>Reservation ID:</strong> 6903442199036</p>
        <p><strong>Guest:</strong> Barbie Jalandoni</p>
        <p><strong>Room Type:</strong> Standard Room (Hot Sale Promo)</p>
    </div>

    <h3 class="text-lg font-semibold mb-2">Reservation Details</h3>
    <table class="min-w-full border-collapse text-sm mb-4">
        <thead>
            <tr class="bg-gray-100">
                <th class="border px-4 py-2">Arrival - Departure</th>
                <th class="border px-4 py-2">Adults</th>
                <th class="border px-4 py-2">Children</th>
                <th class="border px-4 py-2">Nights</th>
                <th class="border px-4 py-2">Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="border px-4 py-2">06/02/2025 - 06/03/2025</td>
                <td class="border px-4 py-2">2</td>
                <td class="border px-4 py-2">0</td>
                <td class="border px-4 py-2">1</td>
                <td class="border px-4 py-2">PHP 2,772.00</td>
            </tr>
            <tr class="font-semibold">
                <td class="border px-4 py-2" colspan="4">Total</td>
                <td class="border px-4 py-2">PHP 2,475.00</td>
            </tr>
        </tbody>
    </table>

    <h3 class="text-lg font-semibold mb-2">Additional Products</h3>
    <table class="min-w-full border-collapse text-sm mb-4">
        <thead>
            <tr class="bg-gray-100">
                <th class="border px-4 py-2">Description</th>
                <th class="border px-4 py-2">Quantity</th>
                <th class="border px-4 py-2">Price</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="border px-4 py-2">Thai Body Massage - 1HR</td>
                <td class="border px-4 py-2">1</td>
                <td class="border px-4 py-2">PHP 750.00</td>
            </tr>
            <tr class="font-semibold">
                <td class="border px-4 py-2" colspan="2">Total</td>
                <td class="border px-4 py-2">PHP 750.00</td>
            </tr>
        </tbody>
    </table>

    <h3 class="text-lg font-semibold mb-2">Summary</h3>
    <table class="min-w-full border-collapse text-sm">
        <tbody>
            <tr>
                <td class="border px-4 py-2 font-semibold">Subtotal</td>
                <td class="border px-4 py-2 text-right">PHP 2,475.00</td>
            </tr>
            <tr>
                <td class="border px-4 py-2 font-semibold">Deposit</td>
                <td class="border px-4 py-2 text-right">PHP 1,761.00</td>
            </tr>
            <tr>
                <td class="border px-4 py-2 font-semibold">Additional Products</td>
                <td class="border px-4 py-2 text-right">PHP 750.00</td>
            </tr>
            <tr>
                <td class="border px-4 py-2 font-semibold">VAT</td>
                <td class="border px-4 py-2 text-right">PHP 297.00</td>
            </tr>
            <tr class="font-semibold">
                <td class="border px-4 py-2">Amount Paid</td>
                <td class="border px-4 py-2 text-right">PHP 0.00</td>
            </tr>
            <tr class="bg-gray-100 font-semibold">
                <td class="border px-4 py-2">Grand Total</td>
                <td class="border px-4 py-2 text-right">PHP 3,522.00</td>
            </tr>
            <tr class="bg-gray-100 font-semibold">
                <td class="border px-4 py-2">Balance Due</td>
                <td class="border px-4 py-2 text-right">PHP 3,522.00</td>
            </tr>
        </tbody>
    </table>

    <p class="text-sm text-gray-600">
        <strong>Status:</strong> <span class="font-medium text-yellow-600">Unpaid</span>
    </p>
</div> --}}



{{-- <h2><strong>PAYMENT DETAILS:</strong></h2>
@if ($payments->isNotEmpty())
<table class="table-auto w-full border-collapse">
    <thead>
        <tr>
            <th class="border px-4 py-2">Payment ID</th>
            <th class="border px-4 py-2">Invoice ID</th>
            <th class="border px-4 py-2">Payment Screenshot</th>
            <th class="border px-4 py-2">Amount Paid</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($payments as $payment)
        <tr>
            <td class="border px-4 py-2">{{ $payment->id }}</td>
            <td class="border px-4 py-2">{{ $payment->invoice_id }}</td>
            <td class="border px-4 py-2">
                {{-- Display the payment screenshot as an image --}}
{{-- @if ($payment->payment_screenshot)
                <img src="{{ asset('storage/' . $payment->payment_screenshot) }}" alt="Payment Screenshot"
                    style="max-width: 200px; max-height: 200px;">
                @else
                No payment screenshot available.
                @endif
            </td>
            <td class="border px-4 py-2">{{ $payment->payment_reference_number ?? 'N/A' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<p>No payments found for this invoice.</p>
@endif --}}



{{-- 
                                                    @if ($activityInCart)
                                                    
                                                        <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                                            <button wire:click="RemoveActivity({{ $activity['activity_id'] }})"
                                                                class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                                                Remove
                                                            </button>
                                                        </td>

                                                    @else
                                                        <button wire:click="addActivityToCart({{ $activity->id }})" 
                                                            class="px-4 py-2 mt-auto flex bg-green-700 bg-opacity-85 hover:bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase transition ease-in-out duration-150"
                                                            wire:loading.attr="disabled">
                                                            <div class="flex items-center justify-center">

                                                                <!-- Spinner -->
                                                                <span wire:loading wire:target="addActivityToCart({{ $activity->id }})"
                                                                    class="mr-2">
                                                                    <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                                                            stroke="currentColor" stroke-width="4"></circle>
                                                                        <path class="opacity-75" fill="currentColor"
                                                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                                                                        </path>
                                                                    </svg>
                                                                </span>

                                                                <!-- Button Text -->
                                                                <span wire:loading.remove wire:target="addActivityToCart({{ $activity->id }})">
                                                                    Add Activity
                                                                </span>

                                                            </div>
                                                        </button>
                                                    @endif --}}