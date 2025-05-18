<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reservation Details</title>
</head>

<body style="font-family: Arial, sans-serif; font-size: 14px; color: #333; background-color: #fff; padding: 30px;">
    <h1 style="text-align: center; color: #166534; font-size: 28px; margin-bottom: 30px;">Canopy Farm PH</h1>
    <h3 style="text-align: center; color: #166534; font-size: 20x; margin-bottom: 30px;">Reservation Details</h3>

    <!-- Guest Details -->
    <div style="border: 1px solid #ccc; border-radius: 8px; padding: 20px; margin-bottom: 30px;">
        <h2 style="color: #166534; font-size: 16px; font-weight: bold; margin-bottom: 15px;">
            Guest Details
        </h2>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="padding: 6px; font-weight: bold;">Guest Name:</td>
                <td style="padding: 6px;">
                    {{ $transaction->transactionUser->first_name }}
                    {{ $transaction->transactionUser->middle_name }}
                    {{ $transaction->transactionUser->last_name }}
                    {{ $transaction->transactionUser->suffix }}
                </td>
            </tr>
            <tr>
                <td style="padding: 6px; font-weight: bold;">Email:</td>
                <td style="padding: 6px;">{{ $transaction->transactionUser->email }}</td>
            </tr>
            <tr>
                <td style="padding: 6px; font-weight: bold;">Contact Number:</td>
                <td style="padding: 6px;">{{ $transaction->transactionUser->contact_number }}</td>
            </tr>
            <tr>
                <td style="padding: 6px; font-weight: bold;">Company Name:</td>
                <td style="padding: 6px;">{{ $transaction->transactionUser->company_name ?? 'Not provided' }}</td>
            </tr>
            <tr>
                <td style="padding: 6px; font-weight: bold;">Country:</td>
                <td style="padding: 6px;">{{ $transaction->transactionUser->country ?? 'Not provided' }}</td>
            </tr>
        </table>
    </div>

    <!-- Additional Guests Details -->
    <div style="border: 1px solid #ccc; border-radius: 8px; padding: 20px;">
        <h2 style="color: #166534; font-size: 16px; font-weight: bold; margin-bottom: 15px;">
            Additional Guests Details
        </h2>

        @if ($guestDetails->isNotEmpty())
        <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
            <thead>
                <tr style="background-color: #f9f9f9;">
                    <th style="border: 1px solid #ccc; padding: 8px; text-align: left;">Full Name</th>
                    <th style="border: 1px solid #ccc; padding: 8px; text-align: left;">Gender</th>
                    <th style="border: 1px solid #ccc; padding: 8px; text-align: left;">Residency</th>
                    <th style="border: 1px solid #ccc; padding: 8px; text-align: left;">Country of Origin</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($guestDetails as $guestDetail)
                <tr>
                    <td style="border: 1px solid #ccc; padding: 8px;">
                        {{ $guestDetail->first_name }}
                        {{ $guestDetail->middle_name }}
                        {{ $guestDetail->last_name }}
                        {{ $guestDetail->suffix }}
                    </td>
                    <td style="border: 1px solid #ccc; padding: 8px;">
                        {{ ucfirst($guestDetail->gender ?? 'N/A') }}
                    </td>
                    <td style="border: 1px solid #ccc; padding: 8px;">
                        {{ ucfirst($guestDetail->residency) }}
                    </td>
                    <td style="border: 1px solid #ccc; padding: 8px;">
                        {{ ucfirst($guestDetail->country_of_origin) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p style="color: #888; font-style: italic;">No additional guests found for this transaction.</p>
        @endif
    </div>

    <!-- TRANSACTION DETAILS (Table Layout) -->
    <div style="background-color: #fff; border: 1px solid #ccc; border-radius: 8px; padding: 20px; margin-top: 30px;">
        <h2 style="color: #166534; font-size: 16px; font-weight: bold; margin-bottom: 15px;">
            Transaction Details
        </h2>

        <!-- Status -->
        @php
        $status = $transaction->transaction_status;
        $statusColors = [
        'pending' => ['#f3f4f6', '#4b5563'],
        'reserved' => ['#dbeafe', '#3b82f6'],
        'receipt_verified' => ['#cffafe', '#06b6d4'],
        'confirmed' => ['#d1fae5', '#059669'],
        'ongoing' => ['#fef9c3', '#ca8a04'],
        'done' => ['#e0e7ff', '#4f46e5'],
        'no_show' => ['#fce7f3', '#ec4899'],
        'terminated' => ['#ffe4e6', '#e11d48'],
        'expired' => ['#ffedd5', '#f97316'],
        'cancelled' => ['#fee2e2', '#dc2626'],
        ];
        $bg = $statusColors[$status][0] ?? '#f3f4f6';
        $color = $statusColors[$status][1] ?? '#374151';
        @endphp

        <table style="width: 100%; border-collapse: collapse; font-size: 14px; color: #374151;">
            <tr>
                <td style="padding: 8px; font-weight: bold; width: 30%;">Transaction Status:</td>
                <td style="padding: 8px;">
                    <span
                        style="display: inline-block; padding: 4px 10px; font-size: 12px; font-weight: bold; background-color: {{ $bg }}; color: {{ $color }}; border-radius: 999px;">
                        {{ ucwords(str_replace('_', ' ', $status)) }}
                    </span>
                </td>
            </tr>
            <tr>
                <td style="padding: 8px; font-weight: bold;">Transaction Number:</td>
                <td style="padding: 8px;">#{{ $transaction->id }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; font-weight: bold;">Reservation Created At:</td>
                <td style="padding: 8px;">{{ $transaction->created_at }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; font-weight: bold;">Reservation Source:</td>
                <td style="padding: 8px;">{{ $transaction->reservation_source }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; font-weight: bold;">Check-in Date:</td>
                <td style="padding: 8px;">{{ \Carbon\Carbon::parse($transaction->start_datetime)->format('F j, Y') }}
                </td>
            </tr>
            <tr>
                <td style="padding: 8px; font-weight: bold;">Check-out Date:</td>
                <td style="padding: 8px;">{{ \Carbon\Carbon::parse($transaction->end_datetime)->format('F j, Y') }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; font-weight: bold;">Duration of Stay:</td>
                <td style="padding: 8px;">{{ $transaction->properties->first()?->pivot->days ?? 'N/A' }} day(s)</td>
            </tr>
            <tr>
                <td style="padding: 8px; font-weight: bold;">Total Adults:</td>
                <td style="padding: 8px;">{{ $transaction->total_adults }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; font-weight: bold;">Total Kids:</td>
                <td style="padding: 8px;">{{ $transaction->total_kids }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; font-weight: bold;">Total Guests:</td>
                <td style="padding: 8px;">{{ $transaction->pax }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; font-weight: bold;">Total Amount:</td>
                <td style="padding: 8px;">PHP{{ number_format($transaction->total_amount, 2) }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; font-weight: bold;">Deposit:</td>
                <td style="padding: 8px;">PHP{{ number_format($transaction->deposit_amount, 2) }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; font-weight: bold;">Heard From:</td>
                <td style="padding: 8px;">{{ $transaction->heard_from }}</td>
            </tr>
        </table>
    </div>



    <!---------------------- ROOM DETAILS  ------------------------------>
    <div style="background-color: #fff; border: 1px solid #ccc; border-radius: 8px; padding: 20px; margin-top: 30px;">
        <h2 style="color: #166534; font-size: 16px; font-weight: bold; margin-bottom: 15px;">
            Room Details
        </h2>

        @if ($properties->isNotEmpty())
        <table style="width: 100%; border-collapse: collapse; font-size: 10px; color: #374151;">
            <thead style="background-color: #f9fafb;">
                <tr>
                    <th style="border: 1px solid #d1d5db; padding: 8px; font-weight: bold;">Room</th>
                    <th style="border: 1px solid #d1d5db; padding: 8px; font-weight: bold;">Category</th>
                    <th style="border: 1px solid #d1d5db; padding: 8px; font-weight: bold; text-align: center;">No. of
                        Adults</th>
                    <th style="border: 1px solid #d1d5db; padding: 8px; font-weight: bold; text-align: center;">No. of
                        Kids</th>
                    <th style="border: 1px solid #d1d5db; padding: 8px; font-weight: bold; text-align: center;">Stay
                        Duration</th>
                    <th style="border: 1px solid #d1d5db; padding: 8px; font-weight: bold; text-align: center;">Extra
                        Guests</th>
                    <th style="border: 1px solid #d1d5db; padding: 8px; font-weight: bold; text-align: right;">Base Rate
                    </th>
                    <th style="border: 1px solid #d1d5db; padding: 8px; font-weight: bold; text-align: right;">Extra
                        Guest Charge</th>
                    <th style="border: 1px solid #d1d5db; padding: 8px; font-weight: bold; text-align: right;">Room
                        Total
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaction->properties as $property)
                <tr>
                    <td style="border: 1px solid #d1d5db; padding: 8px;">{{ $property->name_number }}</td>
                    <td style="border: 1px solid #d1d5db; padding: 8px;">{{ $property->category->name }}</td>
                    <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">{{ $property->pivot->adults
                        ?? 'N/A' }}</td>
                    <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">{{ $property->pivot->kids
                        ?? 'N/A' }}</td>
                    <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">{{ $property->pivot->days
                        ?? 'N/A' }}</td>
                    <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">{{
                        $property->pivot->extra_guest ?? 'N/A' }}</td>
                    <td style="border: 1px solid #d1d5db; padding: 8px; text-align: right;">PHP{{
                        number_format($property->pivot->amount ?? 0, 2) }}</td>
                    <td style="border: 1px solid #d1d5db; padding: 8px; text-align: right;">PHP{{
                        number_format($property->pivot->extra_charge ?? 0, 2) }}</td>
                    <td style="border: 1px solid #d1d5db; padding: 8px; text-align: right; font-weight: bold;">PHP{{
                        number_format($property->pivot->total_amount ?? 0, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="text-align: right; font-weight: bold; font-size: 15px; margin-top: 12px; color: #374151;">
            Total Room Charges: PHP {{ number_format($totalRooms, 2) }}
        </div>
        @else
        <p style="color: #6b7280; font-style: italic;">No properties found for this transaction.</p>
        @endif
    </div>

    <!---------------------------- ADD ON SERVICES ------------------------------------>
    <div style="background-color: #fff; border: 1px solid #ccc; border-radius: 8px; padding: 20px; margin-top: 30px;">
        <h2 style="color: #166534; font-size: 16px; font-weight: bold; margin-bottom: 15px;">
            Add-On Services/Activities
        </h2>

        @if ($transaction->activities->isNotEmpty())
        <table style="width: 100%; border-collapse: collapse; font-size: 14px; color: #374151;">
            <thead style="background-color: #f9fafb;">
                <tr>
                    <th style="border: 1px solid #d1d5db; padding: 8px; font-weight: bold;">Activity Name</th>
                    <th style="border: 1px solid #d1d5db; padding: 8px; font-weight: bold; text-align: center;">Quantity
                    </th>
                    <th style="border: 1px solid #d1d5db; padding: 8px; font-weight: bold; text-align: right;">Unit Cost
                    </th>
                    <th style="border: 1px solid #d1d5db; padding: 8px; font-weight: bold; text-align: right;">Activity
                        Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaction->activities as $activity)
                <tr>
                    <td style="border: 1px solid #d1d5db; padding: 8px;">{{ $activity->name }}</td>
                    <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center;">{{
                        $activity->pivot->quantity ?? 'N/A' }}</td>
                    <td style="border: 1px solid #d1d5db; padding: 8px; text-align: right;">PHP{{
                        number_format($activity->amount ?? 0, 2) }}</td>
                    <td style="border: 1px solid #d1d5db; padding: 8px; text-align: right; font-weight: bold;">
                        PHP{{ number_format(($activity->amount ?? 0) * ($activity->pivot->quantity ?? 0), 2) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="text-align: right; font-weight: bold; font-size: 15px; margin-top: 12px; color: #374151;">
            Total Activity Charges: PHP{{ number_format($totalAddons, 2) }}
        </div>
        @else
        <p style="color: #6b7280; font-style: italic;">No activities found for this transaction.</p>
        @endif
    </div>




    <!---------------------------- INVOICE DETAILS ------------------------------------->
    <div style="background-color: #fff; border: 1px solid #ccc; border-radius: 8px; padding: 20px; margin-top: 30px;">
        <h2 style="color: #166534; font-size: 16px; font-weight: bold; margin-bottom: 15px;">
            Invoice Details
        </h2>
        @if ($invoice)
        <table style="width: 100%; border-collapse: collapse; font-size: 14px; color: #374151;">
            <tr>
                <td style="padding: 8px; font-weight: bold; width: 30%;">Invoice Number:</td>
                <td style="padding: 8px;"># {{ $invoice->invoice_number }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; font-weight: bold;">Due Date:</td>
                <td style="padding: 8px;">
                    {{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('F j, Y') : 'Not yet set'
                    }}
                </td>
            </tr>
            <tr>
                <td style="padding: 8px; font-weight: bold;">Grand Total:</td>
                <td style="padding: 8px; font-weight: bold;">PHP{{ number_format($invoice->sub_total, 2) }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; font-weight: bold;">Deposit:</td>
                <td style="padding: 8px;">PHP{{ number_format($transaction->deposit_amount, 2) }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; font-weight: bold;">Amount Paid:</td>
                <td style="padding: 8px;">PHP{{ number_format($invoice->amount_paid, 2) }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; font-weight: bold;">Balance Due:</td>
                <td style="padding: 8px;">PHP{{ number_format($invoice->balance_due, 2) }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; font-weight: bold;">Invoice Status:</td>
                <td style="padding: 8px; color: #ca8a04;">{{ ucfirst($invoice->invoice_status) }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; font-weight: bold;">Completed At:</td>
                <td style="padding: 8px;">
                    {{ $invoice->completed_at ? \Carbon\Carbon::parse($invoice->completed_at)->format('F j, Y') : 'Not
                    yet completed' }}
                </td>
            </tr>
        </table>
        @else
        <p style="color: #6b7280; font-style: italic;">No invoice found for this transaction.</p>
        @endif
    </div>



    {{-- ------------------------PAYMENT DETAILS --------------------------------- --}}
    <div style="background-color: #fff; border: 1px solid #ccc; border-radius: 8px; padding: 12px; margin-top: 30px;">
        <h2 style="color: #166534; font-size: 16px; font-weight: bold; margin-bottom: 15px;">
            Payments
        </h2>

        @if ($payments->isNotEmpty())
        <table style="width: 100%; border-collapse: collapse; font-size: 10px; color: #374151; table-layout: fixed;">
            <thead style="background-color: #f9fafb;">
                <tr>
                    <th style="border: 1px solid #d1d5db; padding: 4px 6px; font-weight: bold; width: 7%;">ID</th>
                    <th style="border: 1px solid #d1d5db; padding: 4px 6px; font-weight: bold; width: 7%;">Invoice</th>
                    <th style="border: 1px solid #d1d5db; padding: 4px 6px; font-weight: bold; width: 10%;">Method</th>
                    <th
                        style="border: 1px solid #d1d5db; padding: 4px 6px; font-weight: bold; width: 10%; text-align: right;">
                        Amount</th>
                    <th style="border: 1px solid #d1d5db; padding: 4px 6px; font-weight: bold; width: 7%;">Type</th>
                    <th
                        style="border: 1px solid #d1d5db; padding: 4px 6px; font-weight: bold; width: 13%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        Ref #</th>
                    <th style="border: 1px solid #d1d5db; padding: 4px 6px; font-weight: bold; width: 10%;">Date</th>
                    <th style="border: 1px solid #d1d5db; padding: 4px 6px; font-weight: bold; width: 10%;">Status</th>
                    <th style="border: 1px solid #d1d5db; padding: 4px 6px; font-weight: bold; width: 10%;">Notes</th>
                    <th style="border: 1px solid #d1d5db; padding: 4px 6px; font-weight: bold; width: 10%;">Verified
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($payments as $payment)
                <tr>
                    <td style="border: 1px solid #d1d5db; padding: 4px 6px;">{{ $payment->id }}</td>
                    <td style="border: 1px solid #d1d5db; padding: 4px 6px;">{{ $payment->invoice_id }}</td>
                    <td style="border: 1px solid #d1d5db; padding: 4px 6px;">{{
                        $payment->paymentMethod->mode_of_payment_name }}</td>
                    <td style="border: 1px solid #d1d5db; padding: 4px 6px; text-align: right; font-size: 9px;">
                        PHP{{ number_format($payment->amount_paid, 2) }}</td>
                    <td style="border: 1px solid #d1d5db; padding: 4px 6px;">{{ ucfirst($payment->payment_type) }}</td>
                    <td
                        style="border: 1px solid #d1d5db; padding: 4px 6px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ $payment->payment_reference_number ?? 'N/A' }}</td>
                    <td style="border: 1px solid #d1d5db; padding: 4px 6px;">{{ $payment->payment_date ?? 'N/A' }}</td>
                    <td style="border: 1px solid #d1d5db; padding: 4px 6px; text-align: center;">
                        <span style="
                        display: inline-block;
                        padding: 2px 6px;
                        font-size: 9px;
                        font-weight: bold;
                        border-radius: 12px;
                        color:
                            {{ $payment->payment_status === 'pending' ? '#b45309' : 
                               ($payment->payment_status === 'failed' ? '#b91c1c' : '#15803d') }};
                        background-color:
                            {{ $payment->payment_status === 'pending' ? '#fef3c7' : 
                               ($payment->payment_status === 'failed' ? '#fee2e2' : '#d1fae5') }};
                        ">
                            {{ ucfirst($payment->payment_status) }}
                        </span>
                    </td>
                    <td
                        style="border: 1px solid #d1d5db; padding: 4px 6px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ $payment->notes ?? '-' }}</td>
                    <td style="border: 1px solid #d1d5db; padding: 4px 6px;">{{ $payment->verified_at ?? 'To be
                        verified' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p style="font-size: 12px; color: #6b7280; font-style: italic;">No payments found for this invoice.</p>
        @endif
    </div>




</body>

</html>