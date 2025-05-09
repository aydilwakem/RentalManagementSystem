<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Reservation') }}
        </h2>
    </x-slot>

    <div class="py-1">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!---------------------------- GUEST DETAILS ---------------------------------------->
            <div class="bg-white shadow-lg rounded-lg border border-gray-200 p-6">
                <h2 class="font-semibold text-xl text-green-700 leading-tight mb-4">
                    {{ __('Guest Details') }}
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-gray-700">
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
            <div class="bg-white shadow-lg rounded-lg border border-gray-200 p-6">
                <h2 class="font-semibold text-xl text-green-700 leading-tight mb-4">
                    {{ __('Additional Guests Details') }}
                </h2>
                @if ($guestDetails->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse border border-gray-300 text-sm text-left">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="border px-4 py-2 font-medium text-gray-900">Full Name</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900">Gender</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900">Residency</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900">Country of Origin</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                @foreach ($guestDetails as $guestDetail)
                                    <tr>
                                        <td class="border px-4 py-2 text-gray-700">
                                            {{ $guestDetail->first_name }}
                                            {{ $guestDetail->middle_name }}
                                            {{ $guestDetail->last_name }}
                                            {{ $guestDetail->suffix }}
                                        </td>
                                        <td class="border px-4 py-2 text-gray-700">{{ $guestDetail->gender ?? 'N/A' }}
                                        </td>
                                        <td class="border px-4 py-2 text-gray-700">{{ $guestDetail->residency }}</td>
                                        <td class="border px-4 py-2 text-gray-700">
                                            {{ $guestDetail->country_of_origin }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-600 italic">No additional guests found for this transaction.</p>
                @endif
            </div>

            <!---------------------------- TRANSACTION DETAILS --------------------------------->
            <div class="bg-white shadow-lg rounded-lg border border-gray-200 p-6">
                <h2 class="font-semibold text-xl text-green-700 leading-tight mb-4">
                    {{ __('Transaction Details') }}
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-gray-700">
                    <div class="col-span-full">
                        <strong>Transaction Status:</strong>
                        <div class="mt-1">
                            @if ($transaction->transaction_status === 'pending')
                                <span
                                    class="inline-block py-1 px-2 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">Awaiting
                                    Payement</span>
                            @elseif ($transaction->transaction_status === 'reserved')
                                <span
                                    class="inline-block py-1 px-2 rounded-full text-xs font-semibold bg-blue-100 text-blue-500">Pending
                                    Verification</span>
                            @elseif ($transaction->transaction_status === 'receipt_verified')
                                <span
                                    class="inline-block py-1 px-2 rounded-full text-xs font-semibold bg-cyan-100 text-cyan-500">Payment
                                    Verified</span>
                            @elseif ($transaction->transaction_status === 'confirmed')
                                <span
                                    class="inline-block py-1 px-2 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-600">Confirmed</span>
                            @elseif ($transaction->transaction_status === 'ongoing')
                                <span
                                    class="inline-block py-1 px-2 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-600">On-Going
                                    </span>
                            @elseif ($transaction->transaction_status === 'done')
                                <span
                                    class="inline-block py-1 px-2 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-600">Completed</span>
                            @elseif ($transaction->transaction_status === 'no_show')
                                <span
                                    class="inline-block py-1 px-2 rounded-full text-xs font-semibold bg-pink-100 text-pink-500">No
                                    Show</span>
                            @elseif ($transaction->transaction_status === 'terminated')
                                <span
                                    class="inline-block py-1 px-2 rounded-full text-xs font-semibold bg-rose-100 text-rose-600">Terminated</span>
                            @elseif ($transaction->transaction_status === 'expired')
                                <span
                                    class="inline-block py-1 px-2 rounded-full text-xs font-semibold bg-orange-100 text-orange-500">Expired</span>
                            @elseif ($transaction->transaction_status === 'cancelled')
                                <span
                                    class="inline-block py-1 px-2 rounded-full text-xs font-semibold bg-red-100 text-red-600">Cancelled</span>
                            @else
                                {{ ucfirst($transaction->transaction_status) }}
                            @endif
                        </div>
                    </div>
                    <div>
                        <strong>Transaction Number:</strong>
                        <div>#{{ $transaction->id }}</div>
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
                        <strong>Total Amount:</strong>
                        <div>₱{{ number_format($transaction->total_amount, 2) }}</div>
                    </div>
                    <div>
                        <strong>Deposit:</strong>
                        <div>₱{{ number_format($transaction->deposit_amount, 2) }}</div>
                    </div>
                    <div>
                        <strong>Heard From:</strong>
                        <div>{{ $transaction->heard_from }}</div>
                    </div>
                </div>
            </div>

            <!---------------------------- ROOM DETAILS ---------------------------------------->
            <div class="bg-white shadow-lg rounded-lg border border-gray-200 p-6">
                <h2 class="font-semibold text-xl text-green-700 leading-tight mb-4">
                    {{ __('Room Details') }}
                </h2>
                @if ($properties->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse border border-gray-300 text-sm text-left">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="border px-4 py-2 font-medium text-gray-900">Room</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900">Category</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 text-center">No. of Adults
                                    </th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 text-center">No. of Kids</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 text-center">Stay Duration
                                    </th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 text-center">Extra Guests</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 text-right">Base Rate</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 text-right">Extra Guest Charge
                                    </th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 text-right">Room Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                @foreach ($properties as $property)
                                    <tr>
                                        <td class="border px-4 py-2 text-gray-700">{{ $property->name_number }}</td>
                                        <td class="border px-4 py-2 text-gray-700">{{ $property->category->name }}</td>
                                        <td class="border px-4 py-2 text-gray-700 text-center">
                                            {{ $property->pivot->adults ?? 'N/A' }}</td>
                                        <td class="border px-4 py-2 text-gray-700 text-center">
                                            {{ $property->pivot->kids ?? 'N/A' }}</td>
                                        <td class="border px-4 py-2 text-gray-700 text-center">
                                            {{ $property->pivot->days ?? 'N/A' }}</td>
                                        <td class="border px-4 py-2 text-gray-700 text-center">
                                            {{ $property->pivot->extra_guest ?? 'N/A' }}</td>
                                        <td class="border px-4 py-2 text-gray-700 text-right">
                                            ₱{{ number_format($property->pivot->amount ?? 0, 2) }}</td>
                                        <td class="border px-4 py-2 text-gray-700 text-right">
                                            ₱{{ number_format($property->pivot->extra_charge ?? 0, 2) }}</td>
                                        <td class="border px-4 py-2 text-gray-700 text-right font-semibold">
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
            <div class="bg-white shadow-lg rounded-lg border border-gray-200 p-6">
                <h2 class="font-semibold text-xl text-green-700 leading-tight mb-4">
                    {{ __('Add-on Services/Activities') }}
                </h2>
                @if ($activities->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse border border-gray-300 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="border px-4 py-2 font-medium text-gray-900 text-left">Activity Name</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 text-center">Quantity</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 text-right">Unit Cost</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900 text-right">Activity Total
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                @foreach ($activities as $activity)
                                    <tr>
                                        <td class="border px-4 py-2 text-gray-700 text-left">{{ $activity->name }}</td>
                                        <td class="border px-4 py-2 text-gray-700 text-center">
                                            {{ $activity->pivot->quantity }}</td>
                                        <td class="border px-4 py-2 text-gray-700 text-right">
                                            ₱{{ number_format($activity->amount, 2) }}</td>
                                        <td class="border px-4 py-2 text-gray-700 text-right font-semibold">
                                            ₱{{ number_format($activity->amount * $activity->pivot->quantity, 2) }}
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
            <div class="bg-white shadow-lg rounded-lg border border-gray-200 p-6">
                <h2 class="font-semibold text-xl text-green-700 leading-tight mb-4">
                    {{ __('Invoice Details') }}
                </h2>
                @if ($invoice)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-gray-700">
                        <!-- Invoice Info -->
                        <div><strong>Invoice Number:</strong></div>
                        <div># {{ $invoice->invoice_number }}</div>

                        <div><strong>Due Date:</strong></div>
                        <div>
                            {{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('F j, Y') : 'Not yet set' }}
                        </div>

                        <!-- COst Summary -->
                        <div><strong>Grand Total:</strong></div>
                        <div class="font-semibold">₱{{ number_format($invoice->sub_total, 2) }}</div>

                        <div><strong>Deposit:</strong></div>
                        <div>₱{{ number_format($transaction->deposit_amount, 2) }}</div>

                        <div><strong>Amount Paid:</strong></div>
                        <div>₱{{ number_format($invoice->amount_paid, 2) }}</div>

                        <div><strong>Balance Due:</strong></div>
                        <div>₱{{ number_format($invoice->balance_due, 2) }}</div>

                        <!-- Payment Status -->
                        <div><strong>Invoice Status:</strong></div>
                        <div>
                            <div class="text-yellow-500">{{ ucfirst($invoice->invoice_status) }}</div>
                        </div>

                        <!-- Timeline -->
                        <div><strong>Completed At:</strong></div>
                        <div>
                            {{ $invoice->completed_at ? \Carbon\Carbon::parse($invoice->completed_at)->format('F j, Y') : 'Not yet completed' }}
                        </div>
                    </div>
                @else
                    <p class="text-gray-600 italic">No invoice found for this transaction.</p>
                @endif
            </div>

            <!---------------------------- PAYMENT DETAILS ---------------------------------------->
            <div class="bg-white shadow-lg rounded-lg border border-gray-200 p-6">
                <h2 class="font-semibold text-xl text-green-700 leading-tight mb-4">
                    {{ __('Payments') }}
                </h2>
                @if ($payments->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse border border-gray-300 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="border px-4 py-2 font-medium text-gray-900">Payment ID</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900">Invoice ID</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900">Method</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900">Amount Paid</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900">Type</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900">Reference #</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900">Upload Date</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900">Status</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900">Notes</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900">Verified At</th>
                                    <th class="border px-4 py-2 font-medium text-gray-900">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                @foreach ($payments as $payment)
                                    <tr class="hover:bg-gray-50">
                                        <td class="border px-4 py-2 text-gray-700">{{ $payment->id }}</td>
                                        <td class="border px-4 py-2 text-gray-700">{{ $payment->invoice_id }}</td>
                                        <td class="border px-4 py-2 text-gray-700">
                                            {{ $payment->paymentMethod->mode_of_payment_name }}</td>
                                        <td class="border px-4 py-2 text-gray-700">
                                            ₱{{ number_format($payment->amount_paid, 2) }}</td>
                                        <td class="border px-4 py-2 text-gray-700">
                                            {{ ucfirst($payment->payment_type) }}</td>
                                        <td class="border px-4 py-2 text-gray-700">
                                            {{ $payment->payment_reference_number ?? 'N/A' }}</td>
                                        <td class="border px-4 py-2 text-gray-700">
                                            {{ $payment->payment_date ?? 'N/A' }}</td>
                                        <td class="border px-4 py-2">
                                            <span
                                                class="inline-block py-1 px-2 rounded-full text-xs font-semibold
                                                {{ $payment->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-500' : '' }}
                                                {{ $payment->payment_status === 'failed' ? 'bg-red-100 text-red-500' : '' }}
                                                {{ $payment->payment_status === 'completed' ? 'bg-green-100 text-green-500' : '' }}">
                                                {{ ucfirst($payment->payment_status) }}
                                            </span>
                                        </td>
                                        <td class="border px-4 py-2 text-gray-700">{{ $payment->notes ?? '-' }}</td>
                                        <td class="border px-4 py-2 text-gray-700">
                                            {{ $payment->verified_at ?? 'To be verified' }}</td>
                                        <td class="border px-4 py-2 space-x-2">
                                            @if ($payment->payment_status === 'pending')
                                                <a href="{{ route('admin.view-payment-receipt', ['payment' => $payment->id]) }}"
                                                    class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white font-semibold text-center py-2 px-4 rounded text-xs">
                                                    Verify Receipt
                                                </a>
                                            @elseif($payment->payment_status === 'completed' || $payment->payment_status === 'failed')
                                                <a href="{{ route('admin.view-payment-receipt', ['payment' => $payment->id]) }}"
                                                    class="inline-block bg-green-500 hover:bg-green-700 text-white font-semibold text-center py-2 px-4 rounded text-xs">
                                                    View Receipt
                                                </a>
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
