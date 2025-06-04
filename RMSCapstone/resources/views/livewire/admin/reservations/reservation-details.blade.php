<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reservation Details</title>
    <style>
        @page {
            margin: 40px 30px;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            color: #333;
        }

        header {
            text-align: center;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 24px;
            margin: 0;
            color: #065f46;
        }

        h2 {
            font-size: 19px;
            margin: 8px 0 4px;
            color: #065f46;
        }

        p {
            margin: 0;
            line-height: 1.5;
        }

        .date-range {
            margin-bottom: 15px;
            font-size: 13px;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #c0c0c0;
            padding: 8px 10px;
            vertical-align: top;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        th {
            background-color: #e6f7ed;
            color: #065f46;
            font-weight: bold;
            font-size: 12px;
            text-align: left;
        }

        .summary {
            margin-top: 30px;
            padding: 15px;
            background-color: #f0fdf4;
            border: 1px solid #a7f3d0;
            border-radius: 5px;
        }

        .summary p {
            margin: 5px 0;
            font-size: 13px;
        }

        .summary p strong {
            color: #047857;
        }

        footer {
            position: fixed;
            bottom: 30px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #888;
        }

        .page-number {
            position: fixed;
            top: 30px;
            right: 40px;
            font-size: 11px;
            color: #666;
        }

        .page-number:after {
            content: "Page " counter(page);
        }
    </style>
</head>

<body style="font-family: Arial, sans-serif; font-size: 14px; color: #333; background-color: #fff;">
    {{-- Header --}}
    <header style="text-align: center">
        <div class="page-number"></div>
        <img src="{{ public_path('images/canopy-logo.png') }}" alt="Canopy Farm PH" style="max-height: 50px;">
        <h1 style="color: #166534;">Canopy Farm PH</h1>
        <p>006 San Gregorio Extension, Brgy. Buna Cerca, Indang, Philippines</p>
        <p>+63 962 447 9893</p>
        <h2 style="color: #166534; padding-top: 10px;">Reservation Details</h2>
    </header>

    {{-- Guest Details --}}
    <div style="border: 1px solid #ccc; border-radius: 8px; padding: 20px; margin-bottom: 30px;">
        <h2
            style="color: #166534; font-size: 16px; font-weight: bold; margin-bottom: 15px; padding-bottom: 8px; border-bottom: 1px dashed #e0e0e0;">
            Guest Details
        </h2>
        <table style="width: 100%;">
            <tr>
                <td style="padding: 6px; font-weight: bold; font-size: 14px;">Guest Name:</td>
                <td style="padding: 6px; font-size: 14px;">
                    {{ $transaction->transactionUser->first_name }}
                    {{ $transaction->transactionUser->middle_name }}
                    {{ $transaction->transactionUser->last_name }}
                    {{ $transaction->transactionUser->suffix }}
                </td>
            </tr>
            <tr>
                <td style="padding: 6px; font-weight: bold; font-size: 14px;">Email:</td>
                <td style="padding: 6px; font-size: 14px;">{{ $transaction->transactionUser->email }}</td>
            </tr>
            <tr>
                <td style="padding: 6px; font-weight: bold; font-size: 14px;">Contact Number:</td>
                <td style="padding: 6px; font-size: 14px;">{{ $transaction->transactionUser->contact_number }}</td>
            </tr>
            <tr>
                <td style="padding: 6px; font-weight: bold; font-size: 14px;">Company Name:</td>
                <td style="padding: 6px; font-size: 14px;">
                    {{ $transaction->transactionUser->company_name ?? 'Not provided' }}</td>
            </tr>
            <tr>
                <td style="padding: 6px; font-weight: bold; font-size: 14px;">Country:</td>
                <td style="padding: 6px; font-size: 14px;">
                    {{ $transaction->transactionUser->country ?? 'Not provided' }}</td>
            </tr>
        </table>
    </div>

    {{-- Additional Guest Details --}}
    <div style="border: 1px solid #ccc; border-radius: 8px; padding: 20px;">
        <h2
            style="color: #166534; font-size: 16px; font-weight: bold; margin-bottom: 15px; padding-bottom: 8px; border-bottom: 1px dashed #e0e0e0;">
            Additional Guests Details
        </h2>

        @if ($guestDetails->isNotEmpty())
            <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                <thead>
                    <tr style="background-color: #166534; color: #fff;">
                        <th style="border: 1px solid #ccc; padding: 8px; font-size: 14px;">Full Name</th>
                        <th style="border: 1px solid #ccc; padding: 8px; font-size: 14px;">Gender</th>
                        <th style="border: 1px solid #ccc; padding: 8px; font-size: 14px;">Residency</th>
                        <th style="border: 1px solid #ccc; padding: 8px; font-size: 14px;">Country of Origin</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($guestDetails as $guestDetail)
                        <tr>
                            <td style="border: 1px solid #ccc; padding: 8px; font-size: 14px;">
                                {{ $guestDetail->first_name }}
                                {{ $guestDetail->middle_name }}
                                {{ $guestDetail->last_name }}
                                {{ $guestDetail->suffix }}
                            </td>
                            <td style="border: 1px solid #ccc; padding: 8px; font-size: 14px;">
                                {{ ucfirst($guestDetail->gender ?? 'N/A') }}
                            </td>
                            <td style="border: 1px solid #ccc; padding: 8px; font-size: 14px;">
                                {{ ucfirst($guestDetail->residency) }}
                            </td>
                            <td style="border: 1px solid #ccc; padding: 8px; font-size: 14px;">
                                {{ ucfirst($guestDetail->country_of_origin) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="color: #888; font-style: italic; text-align: center; font-size: 14px;">No additional guests found
                for this transaction.</p>
        @endif
    </div>

    {{-- Transaction Details --}}
    <div style="background-color: #fff; border: 1px solid #ccc; border-radius: 8px; padding: 20px; margin-top: 30px;">
        <h2
            style="color: #166534; font-size: 16px; font-weight: bold; margin-bottom: 15px; padding-bottom: 8px; border-bottom: 1px dashed #e0e0e0;">
            Transaction Details
        </h2>

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
                <td style="padding: 8px; font-weight: bold; width: 30%; font-size: 14px;">Transaction Status:</td>
                <td style="padding: 8px; font-size: 14px;">
                    <span
                        style="display: inline-block; padding: 4px 10px; font-size: 12px; font-weight: bold; background-color: {{ $bg }}; color: {{ $color }}; border-radius: 999px;">
                        {{ ucwords(str_replace('_', ' ', $status)) }}
                    </span>
                </td>
            </tr>
            <tr>
                <td style="padding: 8px; font-weight: bold; font-size: 14px;">Transaction Number:</td>
                <td style="padding: 8px; font-size: 14px;"><strong>{{ $transaction->transaction_number }}</strong></td>
            </tr>
            <tr>
                <td style="padding: 8px; font-weight: bold; font-size: 14px;">Reservation Created At:</td>
                <td style="padding: 8px; font-size: 14px;">{{ $transaction->created_at->format('F j, Y, h:i A') }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; font-weight: bold; font-size: 14px;">Reservation Source:</td>
                <td style="padding: 8px; font-size: 14px;">{{ $transaction->reservation_source }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; font-weight: bold; font-size: 14px;">Check-in Date:</td>
                <td style="padding: 8px; font-size: 14px;">
                    {{ \Carbon\Carbon::parse($transaction->start_datetime)->format('F j, Y') }}
                </td>
            </tr>
            <tr>
                <td style="padding: 8px; font-weight: bold; font-size: 14px;">Check-out Date:</td>
                <td style="padding: 8px; font-size: 14px;">
                    {{ \Carbon\Carbon::parse($transaction->end_datetime)->format('F j, Y') }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; font-weight: bold; font-size: 14px;">Duration of Stay:</td>
                <td style="padding: 8px; font-size: 14px;">
                    {{ $transaction->properties->first()?->pivot->days ?? 'N/A' }} day(s)</td>
            </tr>
            <tr>
                <td style="padding: 8px; font-weight: bold; font-size: 14px;">Total Adults:</td>
                <td style="padding: 8px; font-size: 14px;">{{ $transaction->total_adults }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; font-weight: bold; font-size: 14px;">Total Kids:</td>
                <td style="padding: 8px; font-size: 14px;">{{ $transaction->total_kids }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; font-weight: bold; font-size: 14px;">Total Guests:</td>
                <td style="padding: 8px; font-size: 14px;">{{ $transaction->pax }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; font-weight: bold; font-size: 14px;">Total Amount:</td>
                <td style="padding: 8px; font-size: 14px;">PHP{{ number_format($transaction->total_amount, 2) }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; font-weight: bold; font-size: 14px;">Deposit:</td>
                <td style="padding: 8px; font-size: 14px;">PHP{{ number_format($transaction->deposit_amount, 2) }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; font-weight: bold; font-size: 14px;">Heard From:</td>
                <td style="padding: 8px; font-size: 14px;">{{ $transaction->heard_from }}</td>
            </tr>
        </table>
    </div>

    {{-- Room Details --}}
    <div style="background-color: #fff; border: 1px solid #ccc; border-radius: 8px; padding: 20px; margin-top: 30px;">
        <h2
            style="color: #166534; font-size: 16px; font-weight: bold; margin-bottom: 15px; padding-bottom: 8px; border-bottom: 1px dashed #e0e0e0;">
            Room Details
        </h2>

        @if ($properties->isNotEmpty())
            <table style="width: 100%; border-collapse: collapse; font-size: 14px; color: #374151;">
                <thead style="background-color: #166534; color: #fff;">
                    <tr>
                        <th
                            style="border: 1px solid #d1d5db; padding: 8px; font-weight: bold; text-align: center; font-size: 13px;">
                            Room</th>
                        <th
                            style="border: 1px solid #d1d5db; padding: 8px; font-weight: bold; text-align: center; font-size: 13px;">
                            Category</th>
                        <th
                            style="border: 1px solid #d1d5db; padding: 8px; font-weight: bold; text-align: center; font-size: 13px;">
                            No. of Adults</th>
                        <th
                            style="border: 1px solid #d1d5db; padding: 8px; font-weight: bold; text-align: center; font-size: 13px;">
                            No. of Kids</th>
                        <th
                            style="border: 1px solid #d1d5db; padding: 8px; font-weight: bold; text-align: center; font-size: 13px;">
                            Stay Duration</th>
                        <th
                            style="border: 1px solid #d1d5db; padding: 8px; font-weight: bold; text-align: center; font-size: 13px;">
                            Extra Guests</th>
                        <th
                            style="border: 1px solid #d1d5db; padding: 8px; font-weight: bold; text-align: center; font-size: 13px;">
                            Base Rate</th>
                        <th
                            style="border: 1px solid #d1d5db; padding: 8px; font-weight: bold; text-align: center; font-size: 13px;">
                            Extra Guest Charge</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaction->properties as $property)
                        <tr>
                            <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center; font-size: 14px;">
                                {{ $property->name_number }}</td>
                            <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center; font-size: 14px;">
                                {{ $property->category->name }}</td>
                            <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center; font-size: 14px;">
                                {{ $property->pivot->adults ?? 'N/A' }}</td>
                            <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center; font-size: 14px;">
                                {{ $property->pivot->kids ?? 'N/A' }}</td>
                            <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center; font-size: 14px;">
                                {{ $property->pivot->days ?? 'N/A' }}</td>
                            <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center; font-size: 14px;">
                                {{ $property->pivot->extra_guest ?? 'N/A' }}</td>
                            <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center; font-size: 14px;">
                                PHP{{ number_format($property->pivot->amount ?? 0, 2) }}</td>
                            <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center; font-size: 14px;">
                                PHP{{ number_format($property->pivot->extra_charge ?? 0, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="text-align: right; font-weight: bold; font-size: 15px; margin-top: 12px; color: #374151;">
                Total Room Charges: PHP {{ number_format($totalRooms, 2) }}
            </div>
        @else
            <p style="color: #6b7280; font-style: italic; text-align: center; font-size: 14px;">No properties found for
                this transaction.</p>
        @endif
    </div>

    {{-- Activity Details --}}
    <div style="background-color: #fff; border: 1px solid #ccc; border-radius: 8px; padding: 20px; margin-top: 30px;">
        <h2
            style="color: #166534; font-size: 16px; font-weight: bold; margin-bottom: 15px; padding-bottom: 8px; border-bottom: 1px dashed #e0e0e0;">
            Add-On Services/Activities
        </h2>

        @if ($transaction->activities->isNotEmpty())
            <table style="width: 100%; border-collapse: collapse; font-size: 14px; color: #374151;">
                <thead style="background-color: #166534; color: #fff;">
                    <tr>
                        <th
                            style="border: 1px solid #d1d5db; padding: 8px; font-weight: bold; text-align: center; font-size: 14px;">
                            Activity Name</th>
                        <th
                            style="border: 1px solid #d1d5db; padding: 8px; font-weight: bold; text-align: center; font-size: 14px;">
                            Quantity</th>
                        <th
                            style="border: 1px solid #d1d5db; padding: 8px; font-weight: bold; text-align: center; font-size: 14px;">
                            Unit Cost</th>
                        <th
                            style="border: 1px solid #d1d5db; padding: 8px; font-weight: bold; text-align: center; font-size: 14px;">
                            Activity Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaction->activities as $activity)
                        <tr>
                            <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center; font-size: 14px;">
                                {{ $activity->name }}</td>
                            <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center; font-size: 14px;">
                                {{ $activity->pivot->quantity ?? 'N/A' }}</td>
                            <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center; font-size: 14px;">
                                PHP{{ number_format($activity->amount ?? 0, 2) }}</td>
                            <td
                                style="border: 1px solid #d1d5db; padding: 8px; text-align: center; font-weight: bold; font-size: 14px;">
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
            <p style="color: #6b7280; font-style: italic; text-align: center; font-size: 14px;">No activities found for
                this transaction.</p>
        @endif
    </div>

    {{-- Invoice Details --}}
    <div style="background-color: #fff; border: 1px solid #ccc; border-radius: 8px; padding: 20px; margin-top: 30px;">
        <h2
            style="color: #166534; font-size: 16px; font-weight: bold; margin-bottom: 15px; padding-bottom: 8px; border-bottom: 1px dashed #e0e0e0;">
            Invoice Details
        </h2>
        @if ($invoice)
            <table style="width: 100%; border-collapse: collapse; font-size: 14px; color: #374151;">
                <tr>
                    <td style="padding: 8px; font-weight: bold; width: 30%; font-size: 14px;">
                        Invoice Number:</td>
                    <td style="padding: 8px; font-size: 14px;"># {{ $invoice->invoice_number }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: bold; font-size: 14px;">Due Date:</td>
                    <td style="padding: 8px; font-size: 14px;">
                        {{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('F j, Y') : 'Not yet set' }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: bold; font-size: 14px;">Grand Total:</td>
                    <td style="padding: 8px; font-weight: bold; font-size: 14px;">
                        PHP{{ number_format($invoice->sub_total, 2) }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: bold; font-size: 14px;">Deposit:</td>
                    <td style="padding: 8px; font-size: 14px;">
                        PHP{{ number_format($transaction->deposit_amount, 2) }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: bold; font-size: 14px;">Amount Paid:</td>
                    <td style="padding: 8px; font-size: 14px;">
                        PHP{{ number_format($invoice->amount_paid, 2) }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: bold; font-size: 14px;">Balance Due:</td>
                    <td style="padding: 8px; font-size: 14px;">
                        PHP{{ number_format($invoice->balance_due, 2) }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: bold; font-size: 14px;">Invoice Status:
                    </td>
                    <td style="padding: 8px; color: #ca8a04; font-size: 14px;">
                        {{ ucfirst($invoice->invoice_status) }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: bold; font-size: 14px;">Completed At:
                    </td>
                    <td style="padding: 8px; font-size: 14px;">
                        {{ $invoice->completed_at ? \Carbon\Carbon::parse($invoice->completed_at)->format('F j, Y') : 'Not yet completed' }}
                    </td>
                </tr>
            </table>
        @else
            <p style="color: #6b7280; font-style: italic; text-align: center; font-size: 14px;">No invoice found for
                this transaction.</p>
        @endif
    </div>

    {{-- ------------------------PAYMENT DETAILS --------------------------------- --}}
    <div style="background-color: #fff; border: 1px solid #ccc; border-radius: 8px; padding: 12px; margin-top: 30px;">
        <h2
            style="color: #166534; font-size: 16px; font-weight: bold; margin-bottom: 15px; padding-bottom: 8px; border-bottom: 1px dashed #e0e0e0;">
            Payments
        </h2>

        @if ($payments->isNotEmpty())
            <table
                style="width: 100%; border-collapse: collapse; font-size: 14px; color: #374151; table-layout: fixed;">
                <thead style="background-color: #166534; color: #fff;">
                    <tr>
                        <th
                            style="border: 1px solid #d1d5db; padding: 4px 6px; font-weight: bold; width: 5%; text-align: center; font-size: 14px;">
                            ID</th>
                        <th
                            style="border: 1px solid #d1d5db; padding: 4px 6px; font-weight: bold; width: 8%; text-align: center; font-size: 14px;">
                            Invoice</th>
                        <th
                            style="border: 1px solid #d1d5db; padding: 4px 6px; font-weight: bold; width: 10%; text-align: center; font-size: 14px;">
                            Method</th>
                        <th
                            style="border: 1px solid #d1d5db; padding: 4px 6px; font-weight: bold; width: 10%; text-align: center; font-size: 14px;">
                            Amount</th>
                        <th
                            style="border: 1px solid #d1d5db; padding: 4px 6px; font-weight: bold; width: 8%; text-align: center; font-size: 14px;">
                            Type</th>
                        <th
                            style="border: 1px solid #d1d5db; padding: 4px 6px; font-weight: bold; width: 13%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; text-align: center; font-size: 14px;">
                            Ref No.</th>
                        <th
                            style="border: 1px solid #d1d5db; padding: 4px 6px; font-weight: bold; width: 10%; text-align: center; font-size: 14px;">
                            Date</th>
                        <th
                            style="border: 1px solid #d1d5db; padding: 4px 6px; font-weight: bold; width: 10%; text-align: center; font-size: 14px;">
                            Status</th>
                        <th
                            style="border: 1px solid #d1d5db; padding: 4px 6px; font-weight: bold; width: 10%; text-align: center; font-size: 14px;">
                            Verified</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payments as $payment)
                        <tr>
                            <td
                                style="border: 1px solid #d1d5db; padding: 4px 6px; text-align: center; font-size: 14px;">
                                {{ $payment->id }}</td>
                            <td
                                style="border: 1px solid #d1d5db; padding: 4px 6px; text-align: center; font-size: 14px;">
                                {{ $payment->invoice_id }}</td>
                            <td
                                style="border: 1px solid #d1d5db; padding: 4px 6px; text-align: center; font-size: 14px;">
                                {{ $payment->paymentMethod->mode_of_payment_name }}</td>
                            <td
                                style="border: 1px solid #d1d5db; padding: 4px 6px; text-align: center; font-size: 14px;">
                                PHP{{ number_format($payment->amount_paid, 2) }}</td>
                            <td
                                style="border: 1px solid #d1d5db; padding: 4px 6px; text-align: center; font-size: 14px;">
                                {{ ucfirst($payment->payment_type) }}</td>
                            <td
                                style="border: 1px solid #d1d5db; padding: 4px 6px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; text-align: center; font-size: 14px;">
                                {{ $payment->payment_reference_number ?? 'N/A' }}</td>
                            <td
                                style="border: 1px solid #d1d5db; padding: 4px 6px; text-align: center; font-size: 14px;">
                                {{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('M j, Y') : 'N/A' }}
                            </td>
                            <td
                                style="border: 1px solid #d1d5db; padding: 4px 6px; text-align: center; font-size: 14px;">
                                <span
                                    style="display: inline-block; padding: 2px 6px; font-size: 12px; font-weight: bold; border-radius: 12px; color: {{ $payment->payment_status === 'pending' ? '#b45309' : ($payment->payment_status === 'failed' ? '#b91c1c' : '#15803d') }}; background-color: {{ $payment->payment_status === 'pending' ? '#fef3c7' : ($payment->payment_status === 'failed' ? '#fee2e2' : '#d1fae5') }};">
                                    {{ ucfirst($payment->payment_status) }}
                                </span>
                            </td>
                            <td
                                style="border: 1px solid #d1d5db; padding: 4px 6px; text-align: center; font-size: 14px;">
                                {{ $payment->verified_at ? \Carbon\Carbon::parse($payment->verified_at)->format('M j, Y') : 'To be verified' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="font-size: 12px; color: #6b7280; font-style: italic; text-align: center;">No payments found for
                this invoice.</p>
        @endif
    </div>
</body>

</html>
