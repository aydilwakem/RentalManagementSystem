<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Event Details</title>
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
        <h2 style="color: #166534; padding-top: 10px;">Event Booking Details</h2>
    </header>

    {{-- Guest Details --}}
    <div style="border: 1px solid #ccc; border-radius: 8px; padding: 20px; margin-bottom: 30px;">
        <h2
            style="color: #166534; font-size: 16px; font-weight: bold; margin-bottom: 15px; padding-bottom: 8px; border-bottom: 1px dashed #e0e0e0;">
            Booking Contact Details
        </h2>
        <table style="width: 100%;">
            <tr>
                <td style="padding: 6px; font-weight: bold; font-size: 14px;">Guest Name:</td>
                <td style="padding: 6px; font-size: 14px;">
                    {{ $event->transactionUser->first_name }}
                    {{ $event->transactionUser->middle_name }}
                    {{ $event->transactionUser->last_name }}
                    {{ $event->transactionUser->suffix }}
                </td>
            </tr>
            <tr>
                <td style="padding: 6px; font-weight: bold; font-size: 14px;">Email:</td>
                <td style="padding: 6px; font-size: 14px;">{{ $event->transactionUser->email }}</td>
            </tr>
            <tr>
                <td style="padding: 6px; font-weight: bold; font-size: 14px;">Contact Number:</td>
                <td style="padding: 6px; font-size: 14px;">{{ $event->transactionUser->contact_number }}</td>
            </tr>
            <tr>
                <td style="padding: 6px; font-weight: bold; font-size: 14px;">Company Name:</td>
                <td style="padding: 6px; font-size: 14px;">
                    {{ $event->transactionUser->company_name ?? 'Not provided' }}</td>
            </tr>
            <tr>
                <td style="padding: 6px; font-weight: bold; font-size: 14px;">Country:</td>
                <td style="padding: 6px; font-size: 14px;">
                    {{ $event->transactionUser->country ?? 'Not provided' }}</td>
            </tr>
        </table>
    </div>

    {{-- Event Hall Details --}}
    <div style="background-color: #fff; border: 1px solid #ccc; border-radius: 8px; padding: 20px; margin-top: 30px;">
        <h2
            style="color: #166534; font-size: 16px; font-weight: bold; margin-bottom: 15px; padding-bottom: 8px; border-bottom: 1px dashed #e0e0e0;">
            Event Hall Details
        </h2>

        <table style="width: 100%; border-collapse: collapse; font-size: 14px; color: #374151;">
            <thead style="background-color: #166534; color: #fff;">
                <tr>
                    <th
                        style="border: 1px solid #d1d5db; padding: 8px; font-weight: bold; text-align: center; font-size: 13px;">
                        Event Hall</th>
                    <th
                        style="border: 1px solid #d1d5db; padding: 8px; font-weight: bold; text-align: center; font-size: 13px;">
                        Event Type</th>

                    <th
                        style="border: 1px solid #d1d5db; padding: 8px; font-weight: bold; text-align: center; font-size: 13px;">
                        Event Start Date</th>
                    <th
                        style="border: 1px solid #d1d5db; padding: 8px; font-weight: bold; text-align: center; font-size: 13px;">
                        Event End date</th>

                    <th
                        style="border: 1px solid #d1d5db; padding: 8px; font-weight: bold; text-align: center; font-size: 13px;">
                        No. of Adults</th>
                    <th
                        style="border: 1px solid #d1d5db; padding: 8px; font-weight: bold; text-align: center; font-size: 13px;">
                        No. of Kids</th>
                    <th
                        style="border: 1px solid #d1d5db; padding: 8px; font-weight: bold; text-align: center; font-size: 13px;">
                        Total Guests</th>

                    <th
                        style="border: 1px solid #d1d5db; padding: 8px; font-weight: bold; text-align: center; font-size: 13px;">
                        Event Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($event->properties as $property)
                @if ($property->property_type_id == 3)
                <tr>
                    <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center; font-size: 14px;">
                        {{ $property->name_number ?? 'No Event Hall Booked' }}</td>
                    <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center; font-size: 14px;">
                        {{ $event->event_type->name ?? 'N/A' }}</td>
                    <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center; font-size: 14px;">
                        {{ $event->start_datetime->format('F j, Y') }}</td>
                    <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center; font-size: 14px;">
                        {{ $event->end_datetime->format('F j, Y') }}</td>
                    <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center; font-size: 14px;">
                        {{ $event->total_adults }}</td>
                    <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center; font-size: 14px;">
                        {{ $event->total_kids }}</td>
                    <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center; font-size: 14px;">
                        {{ $event->pax }} </td>
                    <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center; font-size: 14px;">
                        {{ ucfirst($event->transaction_status) }}</td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>

        <div style="text-align: right; font-weight: bold; font-size: 15px; margin-top: 12px; color: #374151;">
            Total Agreed Amount: PHP {{ number_format($event->total_amount, 2) }}
        </div>
    </div>


    {{-- Room Details --}}
    @if ($event->properties->where('property_type_id', 1)->count() > 0)
    <div style="background-color: #fff; border: 1px solid #ccc; border-radius: 8px; padding: 20px; margin-top: 30px;">
        <h2
            style="color: #166534; font-size: 16px; font-weight: bold; margin-bottom: 15px; padding-bottom: 8px; border-bottom: 1px dashed #e0e0e0;">
            Room Details
        </h2>

        <table style="width: 100%; border-collapse: collapse; font-size: 14px; color: #374151;">
            <thead style="background-color: #166534; color: #fff;">
                <tr>
                    <th
                        style="border: 1px solid #d1d5db; padding: 8px; font-weight: bold; text-align: center; font-size: 13px;">
                        Room</th>
                    <th
                        style="border: 1px solid #d1d5db; padding: 8px; font-weight: bold; text-align: center; font-size: 13px;">
                        Room Category</th>
                    <th
                        style="border: 1px solid #d1d5db; padding: 8px; font-weight: bold; text-align: center; font-size: 13px;">
                        Ideal Guests</th>
                    <th
                        style="border: 1px solid #d1d5db; padding: 8px; font-weight: bold; text-align: center; font-size: 13px;">
                        Maximum Capacity</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($event->properties as $property)
                @if ($property->property_type_id == 1)
                <tr>
                    <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center; font-size: 14px;">
                        {{ $property->name_number ?? 'No Room Booked' }}</td>
                    <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center; font-size: 14px;">
                        {{ $property->category->name ?? 'N/A' }}</td>
                    <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center; font-size: 14px;">
                        {{ $property->ideal_guest ?? '—' }}</td>

                    @if ($property->occupancy_type === 'whole_number')
                    <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center; font-size: 14px;">
                        {{ $property->max_guests }}</td>

                    @elseif ($property->occupancy_type === 'combinations')
                    @php
                    $originalCombinations = collect($property->occupancy_rules)
                    ->where('type', 'original');

                    $formatted = $originalCombinations->map(function ($combo) {
                    $parts = [];

                    if (!empty($combo['adults'])) {
                    $parts[] = $combo['adults'] . ' adult' . ($combo['adults'] > 1 ? 's' : '');
                    }

                    if (!empty($combo['kids'])) {
                    $parts[] = $combo['kids'] . ' kid' . ($combo['kids'] > 1 ? 's' : '');
                    }

                    return implode(' and ', $parts);
                    });
                    @endphp

                    @if ($formatted->isNotEmpty())
                    <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center; font-size: 14px;">
                        {{ $formatted->implode(' or ') }}</td>
                    @endif
                    @endif
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Catering Details --}}
    @if(!empty($event->dishes) && is_array($event->dishes) && count($event->dishes) > 0)
    <div style="background-color: #fff; border: 1px solid #ccc; border-radius: 8px; padding: 20px; margin-top: 30px;">
        <h2
            style="color: #166534; font-size: 16px; font-weight: bold; margin-bottom: 15px; padding-bottom: 8px; border-bottom: 1px dashed #e0e0e0;">
            Catering Details
        </h2>

        <table style="width: 100%; border-collapse: collapse; font-size: 14px; color: #374151;">
            <thead style="background-color: #166534; color: #fff;">
                <tr>
                    <th
                        style="border: 1px solid #d1d5db; padding: 8px; font-weight: bold; text-align: center; font-size: 13px;">
                        Dishes
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="border: 1px solid #d1d5db; padding: 10px; text-align: center; font-size: 14px;">
                        {{ implode(', ', array_map('trim', $event->dishes)) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    @else
    <div style="background-color: #fff; border: 1px solid #ccc; border-radius: 8px; padding: 20px; margin-top: 30px;">
        <h2
            style="color: #166534; font-size: 16px; font-weight: bold; margin-bottom: 15px; padding-bottom: 8px; border-bottom: 1px dashed #e0e0e0;">
            Catering Details
        </h2>

        <table style="width: 100%; border-collapse: collapse; font-size: 14px; color: #374151;">
            <thead style="background-color: #166534; color: #fff;">
                <tr>
                    <th
                        style="border: 1px solid #d1d5db; padding: 8px; font-weight: bold; text-align: center; font-size: 13px;">
                        Dishes
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td
                        style="border: 1px solid #d1d5db; padding: 10px; text-align: center; font-size: 14px; color: #6b7280;">
                        No Catering Services Availed
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    @endif



    {{-- Added Activities --}}
    <div style="background-color: #fff; border: 1px solid #ccc; border-radius: 8px; padding: 20px; margin-top: 30px;">
        <h2
            style="color: #166534; font-size: 16px; font-weight: bold; margin-bottom: 15px; padding-bottom: 8px; border-bottom: 1px dashed #e0e0e0;">
            Activities Details
        </h2>

        <table style="width: 100%; border-collapse: collapse; font-size: 14px; color: #374151;">
            <thead style="background-color: #166534; color: #fff;">
                <tr>
                    <th
                        style="border: 1px solid #d1d5db; padding: 8px; font-weight: bold; text-align: center; font-size: 13px;">
                        Activity</th>
                    <th
                        style="border: 1px solid #d1d5db; padding: 8px; font-weight: bold; text-align: center; font-size: 13px;">
                        Quantity</th>
                </tr>
            </thead>
            <tbody>
                @if ($event->activities->isEmpty())
                <tr>
                    <td colspan="2"
                        style="border: 1px solid #d1d5db; padding: 10px; text-align: center; font-size: 14px; color: #6b7280;">
                        No additional activities availed
                    </td>
                </tr>
                @else
                @foreach ($event->activities as $activity)
                <tr>
                    <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center; font-size: 14px;">
                        {{ $activity->name ?? 'No Activity Booked' }}</td>
                    <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center; font-size: 14px;">
                        {{ $activity->pivot->quantity ?? 'N/A' }}</td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>

    {{-- Added Services --}}
    <div style="background-color: #fff; border: 1px solid #ccc; border-radius: 8px; padding: 20px; margin-top: 30px;">
        <h2
            style="color: #166534; font-size: 16px; font-weight: bold; margin-bottom: 15px; padding-bottom: 8px; border-bottom: 1px dashed #e0e0e0;">
            Services Details
        </h2>

        <table style="width: 100%; border-collapse: collapse; font-size: 14px; color: #374151;">
            <thead style="background-color: #166534; color: #fff;">
                <tr>
                    <th
                        style="border: 1px solid #d1d5db; padding: 8px; font-weight: bold; text-align: center; font-size: 13px;">
                        Service</th>
                    <th
                        style="border: 1px solid #d1d5db; padding: 8px; font-weight: bold; text-align: center; font-size: 13px;">
                        Quantity</th>
                </tr>
            </thead>
            <tbody>
                @if ($event->services->isEmpty())
                <tr>
                    <td colspan="2"
                        style="border: 1px solid #d1d5db; padding: 10px; text-align: center; font-size: 14px; color: #6b7280;">
                        No additional services availed
                    </td>
                </tr>
                @else
                @foreach ($event->services as $service)
                <tr>
                    <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center; font-size: 14px;">
                        {{ $service->name ?? 'No Service Booked' }}</td>
                    <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center; font-size: 14px;">
                        {{ $service->pivot->quantity ?? 'N/A' }}</td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>

    {{-- Invoice Details --}}
    <div style="background-color: #fff; border: 1px solid #ccc; border-radius: 8px; padding: 20px; margin-top: 30px;">
        <h2
            style="color: #166534; font-size: 16px; font-weight: bold; margin-bottom: 15px; padding-bottom: 8px; border-bottom: 1px dashed #e0e0e0;">
            Event Invoice
        </h2>

        <table style="width: 100%; border-collapse: collapse; font-size: 14px; color: #374151;">
            <thead style="background-color: #166534; color: #fff;">
                <tr>
                    <th
                        style="border: 1px solid #d1d5db; padding: 8px; font-weight: bold; text-align: center; font-size: 13px;">
                        Transaction ID</th>
                    <th
                        style="border: 1px solid #d1d5db; padding: 8px; font-weight: bold; text-align: center; font-size: 13px;">
                        Invoice No.</th>

                    <th
                        style="border: 1px solid #d1d5db; padding: 8px; font-weight: bold; text-align: center; font-size: 13px;">
                        Subtotal</th>
                    <th
                        style="border: 1px solid #d1d5db; padding: 8px; font-weight: bold; text-align: center; font-size: 13px;">
                        Balance Due</th>

                    <th
                        style="border: 1px solid #d1d5db; padding: 8px; font-weight: bold; text-align: center; font-size: 13px;">
                        Due Date</th>
                    <th
                        style="border: 1px solid #d1d5db; padding: 8px; font-weight: bold; text-align: center; font-size: 13px;">
                        Invoice Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center; font-size: 14px;">
                        {{ $event->invoice->transaction->transaction_number ?? 'N/A' }} </td>
                    <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center; font-size: 14px;">
                        {{ $event->invoice->invoice_number }} </td>
                    <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center; font-size: 14px;">
                        {{ number_format($event->invoice->sub_total, 2) }} </td>
                    <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center; font-size: 14px;">
                        {{ number_format($event->invoice->balance_due, 2) }} </td>
                    <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center; font-size: 14px;">
                        {{ $event->invoice->due_date->format('F j, Y') }} </td>
                    <td style="border: 1px solid #d1d5db; padding: 8px; text-align: center; font-size: 14px;">
                        {{ ucfirst($event->invoice->invoice_status) }} </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- ------------------------PAYMENT DETAILS --------------------------------- --}}
    <div style="background-color: #fff; border: 1px solid #ccc; border-radius: 8px; padding: 12px; margin-top: 30px;">
        <h2
            style="color: #166534; font-size: 16px; font-weight: bold; margin-bottom: 15px; padding-bottom: 8px; border-bottom: 1px dashed #e0e0e0;">
            Payments
        </h2>

        @if ($payments->isNotEmpty())
        <table style="width: 100%; border-collapse: collapse; font-size: 14px; color: #374151; table-layout: fixed;">
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
                </tr>
            </thead>
            <tbody>
                @foreach ($payments as $payment)
                <tr>
                    <td style="border: 1px solid #d1d5db; padding: 4px 6px; text-align: center; font-size: 14px;">
                        {{ $payment->id }}</td>
                    <td style="border: 1px solid #d1d5db; padding: 4px 6px; text-align: center; font-size: 14px;">
                        {{ $payment->invoice_id }}</td>
                    <td style="border: 1px solid #d1d5db; padding: 4px 6px; text-align: center; font-size: 14px;">
                        {{ ucfirst($payment->mode_of_payment) }}</td>
                    <td style="border: 1px solid #d1d5db; padding: 4px 6px; text-align: center; font-size: 14px;">
                        {{ number_format($payment->amount_paid, 2) }}</td>
                    <td style="border: 1px solid #d1d5db; padding: 4px 6px; text-align: center; font-size: 14px;">
                        {{ ucfirst($payment->payment_type) }}</td>
                    <td
                        style="border: 1px solid #d1d5db; padding: 4px 6px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; text-align: center; font-size: 14px;">
                        {{ $payment->payment_reference_number ?? 'N/A' }}</td>
                    <td style="border: 1px solid #d1d5db; padding: 4px 6px; text-align: center; font-size: 14px;">
                        {{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('M
                        j,
                        Y') : 'N/A' }}
                    </td>
                    <td style="border: 1px solid #d1d5db; padding: 4px 6px; text-align: center; font-size: 14px;">
                        <span
                            style="display: inline-block; padding: 2px 6px; font-size: 12px; font-weight: bold; border-radius: 12px; color: {{ $payment->payment_status === 'pending' ? '#b45309' : ($payment->payment_status === 'failed' ? '#b91c1c' : '#15803d') }}; background-color: {{ $payment->payment_status === 'pending' ? '#fef3c7' : ($payment->payment_status === 'failed' ? '#fee2e2' : '#d1fae5') }};">
                            {{ ucfirst($payment->payment_status) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p style="font-size: 12px; color: #6b7280; font-style: italic; text-align: center;">No payments
            found
            for
            this invoice.</p>
        @endif
    </div>
</body>


</html>