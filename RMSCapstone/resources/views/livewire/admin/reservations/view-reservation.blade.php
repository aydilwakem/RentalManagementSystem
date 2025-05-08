<div>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Reservation') }}
        </h2>
    </x-slot>

    <div>

        <!---------------------------- GUEST DETAILS ---------------------------------------->
        <h2 class="text-xl font-semibold mt-6 mb-4">Guest Details</h2>
        <div class="bg-white shadow-lg rounded-lg border border-gray-300 p-4">
            <p><strong>Guest Name:</strong> {{ $transaction->transactionUser->first_name }}
                {{ $transaction->transactionUser->middle_name }} {{ $transaction->transactionUser->last_name }}
                {{ $transaction->transactionUser->suffix }}
            </p>
            <p><strong>Email:</strong> {{ $transaction->transactionUser->email }}</p>
            <p><strong>Contact Number:</strong> {{ $transaction->transactionUser->contact_number }}</p>
            <p><strong>Company Name:</strong> {{ $transaction->transactionUser->company_name ?? 'Not provided' }}</p>
            <p><strong>Country:</strong> {{ $transaction->transactionUser->country ?? 'Not provided' }}</p>
        </div>


        <!---------------------------- ADDITIONAL GUESTS DETAILS ---------------------------->
        <h2 class="text-xl font-semibold mt-6 mb-4">Additional Guests Details</h2>
        @if ($guestDetails->isNotEmpty())
            <div class="overflow-x-auto bg-white p-6 rounded-lg shadow-md mb-6">
                <table class="min-w-full border border-gray-300 text-sm text-left">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-4 py-2">Full Name</th>
                            <th class="border px-4 py-2">Gender</th>
                            <th class="border px-4 py-2">Residency</th>
                            <th class="border px-4 py-2">Country of Origin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($guestDetails as $guestDetail)
                            <tr>
                                <td class="border px-4 py-2">
                                    {{ $guestDetail->first_name }}
                                    {{ $guestDetail->middle_name }}
                                    {{ $guestDetail->last_name }}
                                    {{ $guestDetail->suffix }}
                                </td>
                                <td class="border px-4 py-2">{{ $guestDetail->gender ?? 'N/A' }}</td>
                                <td class="border px-4 py-2">{{ $guestDetail->residency}}</td>
                                <td class="border px-4 py-2">{{ $guestDetail->country_of_origin}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p>No additional guest found for this transaction.</p>
        @endif


        <!---------------------------- TRANSACTION DETAILS --------------------------------->
        <h2 class="text-xl font-semibold mt-6 mb-4">Transaction Details</h2>
        <div class="bg-white shadow-lg rounded-lg border border-gray-300 p-4">
            <p><strong>Transaction Number:</strong> #{{ $transaction->id }}</p>

            <p><strong>Check-in Date:</strong>
                {{ \Carbon\Carbon::parse($transaction->start_datetime)->format('F j, Y') }}</p>
            <p><strong>Check-out Date:</strong>
                {{ \Carbon\Carbon::parse($transaction->end_datetime)->format('F j, Y') }}</p>
            <p><strong>Duration of Stay:</strong> {{ $transaction->properties->first()?->pivot->days ?? 'N/A' }} day(s)
            </p>
            <p><strong>Total Adults:</strong> {{ $transaction->total_adults }}</p>
            <p><strong>Total Kids:</strong> {{ $transaction->total_kids }}</p>
            <p><strong>Total Guests:</strong> {{ $transaction->pax }}</p>
            <p><strong>Total Amount:</strong> ₱{{ number_format($transaction->total_amount, 2) }}</p>
            <p><strong>Deposit:</strong> ₱{{ number_format($transaction->deposit_amount, 2) }}</p>
            <p><strong>Heard From:</strong> {{ $transaction->heard_from }}</p>
            <p><strong>Reservation Source:</strong> {{ $transaction->reservation_source }}</p>
            <p><strong>Transaction Status:</strong> {{ ucfirst($transaction->transaction_status) }}</p>
            <p><strong>Reservation Created At:</strong> {{ $transaction->created_at }}</p>
        </div>


        <!---------------------------- INVOICE DETAILS ------------------------------------->
        <h2 class="text-xl font-semibold mt-6 mb-4">Invoice Details</h2>
        @if ($invoice)
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                <div class="font-semibold">Invoice Number:</div>
                <div># {{ $invoice->invoice_number }}</div>

                <div class="font-semibold">Subtotal:</div>
                <div>₱{{ number_format($invoice->sub_total, 2) }}</div>

                <div class="font-semibold">Deposit:</div>
                <div>₱{{ number_format($transaction->deposit_amount, 2) }}</div>

                <div class="font-semibold">Amount Paid:</div>
                <div>₱{{ number_format($invoice->amount_paid, 2) }}</div>

                <div class="font-semibold">Balance Due:</div>
                <div>₱{{ number_format($invoice->balance_due, 2) }}</div>

                <div class="font-semibold">Due Date:</div>
                <div>
                    {{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('F j, Y') : 'Not yet set'
                    }}
                </div>

                <div class="font-semibold">Invoice Status:</div>
                <div class="text-yellow-500">{{ ucfirst($invoice->invoice_status) }}</div>

                <div class="font-semibold">Completed At:</div>
                <div>
                    {{ $invoice->completed_at ? \Carbon\Carbon::parse($invoice->completed_at)->format('F j, Y') : 'Not
                    yet completed' }}
                </div>
            </div>
        </div>
        @else
        <p>No invoice found for this transaction.</p>
        @endif


        <!---------------------------- ROOM DETAILS ---------------------------------------->
        <h2 class="text-xl font-semibold mt-6 mb-4">Room Details</h2>
        @if ($properties->isNotEmpty())
            <div class="overflow-x-auto bg-white p-6 rounded-lg shadow-md mb-6">
                <table class="min-w-full border border-gray-300 text-sm text-left">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-4 py-2">Room Name</th>
                            <th class="border px-4 py-2">Room Type</th>
                            <th class="border px-4 py-2 text-center">Adults</th>
                            <th class="border px-4 py-2 text-center">Kids</th>
                            <th class="border px-4 py-2 text-center">Days</th>
                            <th class="border px-4 py-2 text-center">Extra Guest</th>
                            <th class="border px-4 py-2 text-right">Included Rate</th>
                            <th class="border px-4 py-2 text-right">Extra Charge</th>
                            <th class="border px-4 py-2 text-right">Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($properties as $property)
                            <tr>
                                <td class="border px-4 py-2">{{ $property->name_number }}</td>
                                <td class="border px-4 py-2">{{ $property->category->name }}</td>
                                <td class="border px-4 py-2 text-center">{{ $property->pivot->adults ?? 'N/A' }}</td>
                                <td class="border px-4 py-2 text-center">{{ $property->pivot->kids ?? 'N/A' }}</td>
                                <td class="border px-4 py-2 text-center">{{ $property->pivot->days ?? 'N/A' }}</td>
                                <td class="border px-4 py-2 text-center">{{ $property->pivot->extra_guest ?? 'N/A' }}</td>
                                <td class="border px-4 py-2 text-right">₱{{ number_format($property->pivot->amount ?? 0, 2) }}
                                </td>
                                <td class="border px-4 py-2 text-right">
                                    ₱{{ number_format($property->pivot->extra_charge ?? 0, 2) }}</td>
                                <td class="border px-4 py-2 text-right font-semibold">
                                    ₱{{ number_format($property->pivot->total_amount ?? 0, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="text-right font-semibold text-base mt-2">
                    Total Amount of Rooms: ₱{{ number_format($totalRooms, 2) }}
                </div>
            </div>
        @else
            <p>No properties found for this transaction.</p>
        @endif


        <!---------------------------- ADD ON SERVICES ------------------------------------>
        <h2 class="text-xl font-semibold mt-6 mb-4">Add-on Services/Activities</h2>
        @if ($activities->isNotEmpty())
            <div class="overflow-x-auto bg-white p-6 rounded-lg shadow-md mb-6">
                <table class="table-auto w-full border border-gray-300 text-sm mb-4">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-4 py-2 text-left">Activity</th>
                            <th class="border px-4 py-2 text-center">Quantity</th>
                            <th class="border px-4 py-2 text-right">Unit Price</th>
                            <th class="border px-4 py-2 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($activities as $activity)
                            <tr>
                                <td class="border px-4 py-2">{{ $activity->name }}</td>
                                <td class="border px-4 py-2 text-center">{{ $activity->pivot->quantity }}</td>
                                <td class="border px-4 py-2 text-right"> ₱{{ number_format($activity->amount, 2) }}</td>
                                <td class="border px-4 py-2 text-right">
                                    ₱{{ number_format($activity->amount * $activity->pivot->quantity, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="text-right font-semibold text-base mt-2">
                    Total Add-ons: ₱{{ number_format($totalAddons, 2) }}
                </div>
            </div>
        @else
            <p class="text-gray-600 italic">No activities found for this transaction.</p>
        @endif


        <!---------------------------- PAYMENT DETAILS ---------------------------------------->
        <h2 class="text-xl font-semibold mt-6 mb-4">Payments</h2>
        @if ($payments->isNotEmpty())
            <div class="overflow-x-auto bg-white p-6 rounded-lg shadow-md">
                <table class="min-w-full border-collapse text-sm text-left">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-4 py-2">Payment ID</th>
                            <th class="border px-4 py-2">Invoice ID</th>
                            <th class="border px-4 py-2">Method</th>
                            <th class="border px-4 py-2">Amount Paid</th>
                            <th class="border px-4 py-2">Type</th>
                            <th class="border px-4 py-2">Reference #</th>
                            <th class="border px-4 py-2">Upload Date</th>
                            <th class="border px-4 py-2">Status</th>
                            <th class="border px-4 py-2">Notes</th>
                            <th class="border px-4 py-2">Verified At</th>
                            <th class="border px-4 py-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $payment)
                            <tr class="hover:bg-gray-50">
                                <td class="border px-4 py-2">{{ $payment->id }}</td>
                                <td class="border px-4 py-2">{{ $payment->invoice_id }}</td>
                                <td class="border px-4 py-2">{{ $payment->paymentMethod->mode_of_payment_name }}</td>
                                <td class="border px-4 py-2">₱{{ number_format($payment->amount_paid, 2) }}</td>
                                <td class="border px-4 py-2">{{ ucfirst($payment->payment_type) }}</td>
                                <td class="border px-4 py-2">{{ $payment->payment_reference_number ?? 'N/A' }}</td>
                                <td class="border px-4 py-2">{{ $payment->payment_date ?? 'N/A' }}</td>
                                <td class="border px-4 py-2">
                                    <span
                                        class="text-sm 
                                                                                                                                                                                                        {{ $payment->payment_status === 'pending' ? 'text-yellow-500' : '' }}
                                                                                                                                                                                                        {{ $payment->payment_status === 'failed' ? 'text-red-500' : '' }}
                                                                                                                                                                                                        {{ $payment->payment_status === 'completed' ? 'text-green-500' : '' }}">
                                        {{ ucfirst($payment->payment_status) }}
                                    </span>
                                </td>
                                <td class="border px-4 py-2">{{ $payment->notes ?? '-' }}</td>
                                <td class="border px-4 py-2">{{ $payment->verified_at ?? 'To be verified' }}</td>
                                <td class="border px-4 py-2 space-x-2">
                                    @if($payment->payment_status === 'pending')
                                        <a href="{{ route('admin.view-payment-receipt', ['payment' => $payment->id]) }}"
                                            class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                                            Verify Receipt
                                        </a>
                                    @elseif($payment->payment_status === 'completed' || $payment->payment_status === 'failed')
                                        <a href="{{ route('admin.view-payment-receipt', ['payment' => $payment->id]) }}"
                                            class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
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
            <p>No payments found for this invoice.</p>
        @endif




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