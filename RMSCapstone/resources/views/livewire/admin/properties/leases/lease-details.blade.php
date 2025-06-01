<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body style="font-family: Arial, sans-serif; color: #333333; font-size: 14px; line-height: 1.6;">
    <h1 style="font-size: 24px; font-weight: bold; color: #166534; margin-bottom: 16px; text-align:center;">Canopy Farm
        PH Property Leases</h1>
    <h3 style="font-size: 24px; font-weight: bold; color: #166534; margin-bottom: 16px; text-align:center;">Lease
        Details</h3>

    <div
        style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 24px; margin-bottom: 24px;">
        <div style="display: flex; flex-wrap: wrap; gap: 24px;">

            <!-- Guest Details -->
            <div style="flex: 1 1 300px; margin-bottom: 16px;">
                <h3
                    style="font-size: 18px; font-weight: bold; color: #166534; margin-bottom: 8px; border-bottom: 1px solid #ccc; padding-bottom: 4px;">
                    Tenant Details</h3>
                <div><strong>Tenant Name:</strong> {{ $transaction->transactionUser->first_name }} {{
                    $transaction->transactionUser->middle_name }} {{ $transaction->transactionUser->last_name }}</div>
                <div><strong>Email:</strong> {{ $transaction->transactionUser->email }}</div>
                <div><strong>Contact Number:</strong> {{ $transaction->transactionUser->contact_number }}</div>
            </div>

            <!-- Rent Info -->
            <div style="flex: 1 1 300px; margin-bottom: 16px;">
                <h3
                    style="font-size: 18px; font-weight: bold; color: #166534; margin-bottom: 8px; border-bottom: 1px solid #ccc; padding-bottom: 4px;">
                    Rent Information</h3>
                <div><strong>Property Rented:</strong>
                    @foreach ($transaction->properties as $property)
                    {{ $property->name_number ?? 'No House Found' }}<br>
                    @endforeach
                </div>
                <div><strong>Monthly Rent:</strong> PHP
                    @foreach ($transaction->properties as $property)
                    {{ number_format($property->amount ?? 'N/A', 2) }}<br>
                    @endforeach
                </div>
                <div><strong>Lease Start Date:</strong> {{ $transaction->start_datetime->format('F j, Y') }}</div>
                <div><strong>Lease End Date:</strong> {{ $transaction->end_datetime->format('F j, Y') }}</div>
                <div>
                    <strong>Total Months Stay:</strong>
                    {{ $transaction->start_datetime->diffInMonths($transaction->end_datetime) + 1 }}
                    {{ Str::plural('month', $transaction->start_datetime->diffInMonths($transaction->end_datetime) + 1)
                    }}
                </div>
                <div><strong>Total People Staying:</strong> {{ $transaction->pax }}</div>
                <div><strong>Rent Status:</strong> {{ ucfirst($transaction->transaction_status) }}</div>
                <div><strong>Total Rent In Duration:</strong> PHP {{ number_format($transaction->total_amount, 2) }}
                </div>
            </div>

            <!-- Invoice Info -->
            <div style="flex: 1 1 300px; margin-bottom: 16px;">
                <h3
                    style="font-size: 18px; font-weight: bold; color: #166534; margin-bottom: 8px; border-bottom: 1px solid #ccc; padding-bottom: 4px;">
                    Lease Invoice</h3>
                <div><strong>Transaction ID:</strong> {{ $transaction->transaction_number ?? 'N/A'
                    }}
                </div>
                <div><strong>Invoice Number:</strong> {{ $transaction->invoice->invoice_number }}</div>
                <div><strong>Sub Total:</strong> PHP {{ number_format($transaction->invoice->sub_total, 2) }}</div>
                <div><strong>Balance Due:</strong> PHP {{ number_format($transaction->invoice->balance_due, 2) }}</div>
                <div><strong>Due Date:</strong> {{ $transaction->invoice->due_date->format('F j, Y') }}</div>
                <div><strong>Invoice Status:</strong> {{ ucfirst($transaction->invoice->invoice_status) }}</div>
            </div>

        </div>
    </div>
</body>


</html>