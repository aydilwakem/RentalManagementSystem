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
        PH Events</h1>
    <h3 style="font-size: 24px; font-weight: bold; color: #166534; margin-bottom: 16px; text-align:center;">Event
        Details</h3>

    <div
        style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 24px; margin-bottom: 24px;">
        <div style="display: flex; flex-wrap: wrap; gap: 24px;">

            <!-- Guest Details -->
            <div style="flex: 1 1 300px; margin-bottom: 16px;">
                <h3
                    style="font-size: 18px; font-weight: bold; color: #166534; margin-bottom: 8px; border-bottom: 1px solid #ccc; padding-bottom: 4px;">
                    Guest Details</h3>
                <div><strong>Event Booked By:</strong> {{ $event->transactionUser->first_name }} {{
                    $event->transactionUser->middle_name }} {{ $event->transactionUser->last_name }}</div>
                <div><strong>Email:</strong> {{ $event->transactionUser->email }}</div>
                <div><strong>Contact Number:</strong> {{ $event->transactionUser->contact_number }}</div>
                <div><strong>Company Name:</strong> {{ $event->transactionUser->company_name }}</div>
                <div><strong>Address:</strong> {{ $event->transactionUser->city_municipality }}, {{
                    $event->transactionUser->country }}</div>
            </div>

            <!-- Event Info -->
            <div style="flex: 1 1 300px; margin-bottom: 16px;">
                <h3
                    style="font-size: 18px; font-weight: bold; color: #166534; margin-bottom: 8px; border-bottom: 1px solid #ccc; padding-bottom: 4px;">
                    Event Information</h3>
                <div><strong>Event Hall:</strong>
                    @foreach ($event->properties as $property)
                    {{ $property->name_number ?? 'No Event Hall Booked' }}<br>
                    @endforeach
                </div>
                <div><strong>Event Type:</strong> {{ $event->event_type->name ?? 'N/A' }}</div>
                <div><strong>Event Start Date:</strong> {{ $event->start_datetime->format('F j, Y') }}</div>
                <div><strong>Event End Date:</strong> {{ $event->end_datetime->format('F j, Y') }}</div>
                <div><strong>Total Adults:</strong> {{ $event->total_adults }}</div>
                <div><strong>Total Kids:</strong> {{ $event->total_kids }}</div>
                <div><strong>Total People:</strong> {{ $event->pax }}</div>
                <div><strong>Event Status:</strong> {{ ucfirst($event->transaction_status) }}</div>
                <div><strong>Total Agreed Amount:</strong> PHP {{ number_format($event->total_amount, 2) }}</div>
            </div>

            <!-- Invoice Info -->
            <div style="flex: 1 1 300px; margin-bottom: 16px;">
                <h3
                    style="font-size: 18px; font-weight: bold; color: #166534; margin-bottom: 8px; border-bottom: 1px solid #ccc; padding-bottom: 4px;">
                    Event Invoice</h3>
                <div><strong>Transaction ID:</strong> {{ $event->invoice->transaction_id ?? 'N/A' }}</div>
                <div><strong>Invoice Number:</strong> {{ $event->invoice->invoice_number }}</div>
                <div><strong>Sub Total:</strong> PHP {{ number_format($event->invoice->sub_total, 2) }}</div>
                <div><strong>Balance Due:</strong> PHP {{ number_format($event->invoice->balance_due, 2) }}</div>
                <div><strong>Due Date:</strong> {{ $event->invoice->due_date->format('F j, Y') }}</div>
                <div><strong>Invoice Status:</strong> {{ ucfirst($event->invoice->invoice_status) }}</div>
            </div>

        </div>
    </div>
</body>


</html>