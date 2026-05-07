<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Guest Details Summary</title>
</head>
<meta charset="UTF-8">
<title>Canopy Farm PH - Reservations Summary Report</title>
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
        margin-bottom: 5px;
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

<body>
    <header>
        <div class="page-number"></div>
        <img src="{{ public_path('images/canopy-logo.png') }}" alt="Canopy Farm PH" style="max-height: 50px;">
        <h1>Canopy Farm PH</h1>
        <p>006 San Gregorio Extension, Brgy. Buna Cerca, Indang, Philippines</p>
        <p>+63 962 447 9893</p>
        <h2>Reservations Summary</h2>
        <p class="date-range">
            <strong>Reporting Period:</strong>
            @if ($start_date && $end_date)
            {{ \Carbon\Carbon::parse($start_date)->format('F d, Y') }}
            &ndash;
            {{ \Carbon\Carbon::parse($end_date)->format('F d, Y') }}
            @else
            All Records
            @endif
        </p>
        <p class="date-range">
            <strong>Room:</strong>
            {{ $roomFilter ? $rooms->firstWhere('id', $roomFilter)?->name_number ?? 'Unknown Room' : 'All Rooms' }}

        </p>
        <p class="date-range">
            <strong>Status:</strong>
            {{ empty($reservationStatusFilter) ? 'All Statuses' : $reservationStatusFilter }}
        </p>
    </header>

    <h2 style="text-align:center;">Guest Details Summary Report</h2>

    <table border="1" cellspacing="0" cellpadding="5" width="100%">
        <thead>
            <tr>
                <th>Country of Residence</th>
                <th>Total Guest Check-Ins</th>
            </tr>
        </thead>
        <tbody>
            @foreach($guestByCountry as $country => $data)
            <tr>
                <td>{{ $country }}</td>
                <td>{{ $data['checkins'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <p><strong>Total Guests Checked-In:</strong> {{ $totalGuests }}</p>
        <p><strong>Filipinos:</strong> {{ $totalFilipinos }}</p>
        <p><strong>Foreign Nationals:</strong> {{ $totalForeigners }}</p>
        <p><strong>Total Reservations Within Date Range: </strong>{{ $totalReservations }} reservations</p>
        <p><strong>Total Reservation Nights Within Date Range: </strong>{{ $totalNights }} nights</p>
    </div>

</body>

</html>