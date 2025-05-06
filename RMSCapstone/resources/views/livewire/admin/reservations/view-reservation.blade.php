<div>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Receipt') }}
        </h2>
    </x-slot>

    <div>
        <h2> <strong>GUEST DETAILS:</strong></h2>
        <p><strong>Guest Name:</strong> {{ $transaction->transactionUser->first_name }}
            {{ $transaction->transactionUser->middle_name }} {{ $transaction->transactionUser->last_name }}
            {{ $transaction->transactionUser->suffix }}
        </p>
        <p><strong>Email:</strong> {{ $transaction->transactionUser->email }}</p>
        <p><strong>Contact Number:</strong> {{ $transaction->transactionUser->contact_number }}</p>
        <p><strong>Company Name:</strong> {{ $transaction->transactionUser->company_name ?? 'N/A' }}</p>
        <p><strong>Country:</strong> {{ $transaction->transactionUser->country ?? 'N/A' }}</p>

        <h1>------------------------------------</h1>

        <h2> <strong>TRANSACTION DETAILS:</strong></h2>
        <p><strong>Transaction ID:</strong> {{ $transaction->id }}</p>
        <p><strong>Check-in Date:</strong> {{ $transaction->start_datetime }}</p>
        <p><strong>Check-out Date:</strong> {{ $transaction->end_datetime }}</p>
        <p><strong>Stay Duration:</strong> {{ optional($transaction->properties->first()->pivot)->days ?? 'N/A' }}
            day(s)
        </p>
        <p><strong>Total Adults:</strong> {{ $transaction->total_adults }}</p>
        <p><strong>Total Kids:</strong> {{ $transaction->total_kids }}</p>
        <p><strong>Total Pax:</strong> {{ $transaction->total_pax }}</p>
        <p><strong>Total Amount:</strong> {{ $transaction->total_amount }}</p>
        <p><strong>Deposit:</strong> {{ $transaction->deposit_amount }}</p>
        <p><strong>Heard From:</strong> {{ $transaction->heard_from }}</p>
        <p><strong>Reservation Source:</strong> {{ $transaction->reservation_source }}</p>
        <p><strong>Deposit:</strong> {{ $transaction->transaction_status }}</p>
        <p><strong>Reservation Created At:</strong> {{ $transaction->created_at }}</p>


        <h1>------------------------------------</h1>


        <h2><strong>INVOICE DETAILS:</strong></h2>
        @if ($invoice)
            <p><strong>Invoice Number:</strong> {{ $invoice->invoice_number }}</p>
            <p><strong>Subtotal:</strong> {{ $invoice->sub_total }}</p>
            <p><strong>Deposit Paid:</strong> {{ $invoice->deposit_paid }}</p>
            <p><strong>Amount Paid:</strong> {{ $invoice->amount_paid }}</p>
            <p><strong>Balance Due:</strong> {{ $invoice->balance_due }}</p>
            <p><strong>Due Date:</strong> {{ $invoice->due_date }}</p>
            <p><strong>Invoice Status:</strong> {{ $invoice->invoice_status }}</p>
            <p><strong>Completed At:</strong> {{ $invoice->completed_at }}</p>
        @else
            <p>No invoice found for this transaction.</p>
        @endif

        <h1>------------------------------------</h1>
        <h2><strong>PROPERTY DETAILS:</strong></h2>

        @if ($transaction->properties->isNotEmpty())
            <table class="table-auto w-full border-collapse">
                <thead>
                    <tr>
                        <th class="border px-4 py-2">Room Name</th>
                        <th class="border px-4 py-2">Room Type</th>
                        <th class="border px-4 py-2">Adults</th>
                        <th class="border px-4 py-2">Kids</th>
                        <th class="border px-4 py-2">Days</th>
                        <th class="border px-4 py-2">Extra Guest</th>
                        <th class="border px-4 py-2">Base Rate</th>
                        <th class="border px-4 py-2">Extra Charge</th>
                        <th class="border px-4 py-2">Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaction->properties as $property)
                        <tr>
                            <td class="border px-4 py-2">{{ $property->name_number }}</td>
                            <td class="border px-4 py-2">{{ $property->category->name }}</td>
                            <td class="border px-4 py-2">{{ $property->pivot->adults ?? 'N/A' }}</td>
                            <td class="border px-4 py-2">{{ $property->pivot->kids ?? 'N/A' }}</td>
                            <td class="border px-4 py-2">{{ $property->pivot->days ?? 'N/A' }}</td>
                            <td class="border px-4 py-2">{{ $property->pivot->extra_guest ?? 'N/A' }}</td>
                            <td class="border px-4 py-2">{{ $property->pivot->amount ?? 'N/A' }}</td>
                            <td class="border px-4 py-2">{{ $property->pivot->extra_charge ?? 'N/A' }}</td>
                            <td class="border px-4 py-2">{{ $property->pivot->total_amount ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No properties found for this transaction.</p>
        @endif


        <h1>------------------------------------</h1>
        <h2><strong>ACTIVITY DETAILS:</strong></h2>

        @if ($transaction->activities->isNotEmpty())
            <table class="table-auto w-full border-collapse">
                <thead>
                    <tr>
                        <th class="border px-4 py-2">Activity Name</th>
                        <th class="border px-4 py-2">Activity Date</th>
                        <th class="border px-4 py-2">Rate</th>
                        <th class="border px-4 py-2">Quantity</th>
                        <th class="border px-4 py-2">Total Amount</th>
                        <th class="border px-4 py-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaction->activities as $activity)
                        <tr>
                            <td class="border px-4 py-2">{{ $activity->name }}</td>
                            <td class="border px-4 py-2">
                                {{ \Carbon\Carbon::parse($activity->pivot->activity_datetime)->toDateString() }}
                            </td>
                            <td class="border px-4 py-2">₱{{ number_format($activity->pivot->amount, 2) }}</td>
                            <td class="border px-4 py-2">{{ $activity->pivot->quantity }}</td>
                            <td class="border px-4 py-2">
                                ₱{{ number_format($activity->pivot->amount * $activity->pivot->quantity, 2) }}</td>
                            <td class="border px-4 py-2">{{ $activity->pivot->status }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No activities found for this transaction.</p>
        @endif

        <h1>------------------------------------</h1>

        <h2><strong>CONFIRM RECEIPT:</strong></h2>

        @if ($payments->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-300 text-sm text-left">
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
                            <th class="border px-4 py-2">Paid At</th>
                            <th class="border px-4 py-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $payment)
                            <tr>
                                <td class="border px-4 py-2">{{ $payment->id }}</td>
                                <td class="border px-4 py-2">{{ $payment->invoice_id }}</td>
                                <td class="border px-4 py-2">{{ $payment->paymentMethod->mode_of_payment_name }}</td>
                                <td class="border px-4 py-2">₱{{ number_format($payment->amount_paid, 2) }}</td>
                                <td class="border px-4 py-2">{{ ucfirst($payment->payment_type) }}</td>
                                <td class="border px-4 py-2">{{ $payment->payment_reference_number ?? 'N/A' }}</td>
                                <td class="border px-4 py-2">{{ $payment->payment_date ?? 'N/A' }}</td>
                                <td class="border px-4 py-2">{{ ucfirst($payment->payment_status) }}</td>
                                <td class="border px-4 py-2">{{ $payment->notes ?? '-' }}</td>
                                <td class="border px-4 py-2">{{ $payment->paid_at ?? 'To be verified' }}</td>
                                <td class="border px-4 py-2 space-x-2">
                                <td class="border px-4 py-2 space-x-2">
                                    <a href="{{ route('admin.confirm-receipt', ['transaction' => $transaction->id]) }}"
                                        class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                                        Confirm Receipt
                                    </a>
                                    <button class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600"
                                        wire:click="viewReceipt({{ $payment->id }})">
                                        View Receipt
                                    </button>
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