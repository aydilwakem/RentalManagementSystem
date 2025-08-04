<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Canopy Farm PH - Audit Logs Summary Report</title>
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
</head>

<body>
    <header>
        <div class="page-number"></div>
        <img src="{{ public_path('images/canopy-logo.png') }}" alt="Canopy Farm PH" style="max-height: 50px;">
        <h1>Canopy Farm PH</h1>
        <p>006 San Gregorio Extension, Brgy. Buna Cerca, Indang, Philippines</p>
        <p>+63 962 447 9893</p>
        <h2>Events Summary</h2>
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
            <strong>Module:</strong>
            {{ $logNameFilter ? $logs->firstWhere('id', $logNameFilter)?->log_name ?? 'Unknown Module' : 'All Modules' }}

        </p>
        <p class="date-range">
            <strong>Event:</strong>
            {{ $eventStatusFilter ? $logs->firstWhere('id', $eventStatusFilter)?->event ?? 'Unknown Event' : 'All Events' }}
        </p>
    </header>

    <p>Report generated on {{ now()->format('F d, Y h:i A') }}</p>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 10%;">Log No.</th>
                <th style="width: 15%;">Log Name</th>
                <th style="width: 25%;">Description</th>
                <th style="width: 10%;">Subject ID</th>
                <th style="width: 10%;">Causer Name</th>
                {{-- <th style="width: 25%;">Properties</th> --}}
            </tr>
        </thead>
        <tbody>
            @forelse ($logs as $log)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $log->id }}</td>
                    <td>{{ $log->log_name }}</td>
                    <td>{{ $log->description }}</td>
                    <td>{{ $log->subject_id ?? 'N/A' }}</td>
                    <td>
                        @if ($log->causer)
                            {{ $log->causer->name }} {{ $log->causer->last_name }}
                        @else
                            System
                        @endif
                    </td>
                    {{-- <td>
                        @php
                        $changes = $log->properties->toArray();
                        $old = $changes['old'] ?? [];
                        $new = $changes['attributes'] ?? [];
                        @endphp

                        @if ($old && $new)
                        <ul>
                            @foreach ($new as $key => $newValue)
                            @php
                            $oldValue = $old[$key] ?? 'N/A';
                            $formattedKey = ucfirst(str_replace('_', ' ', $key));
                            @endphp
                            @if ($oldValue != $newValue)
                            <li><strong>{{ $formattedKey }}:</strong> "{{ $oldValue }}" → "{{ $newValue }}"</li>
                            @endif
                            @endforeach
                        </ul>
                        @else
                        <em>No detailed changes</em>
                        @endif
                    </td> --}}
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px;">
                        No audit logs found for the selected filters.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <footer>
        <div class="page-number"></div>
        &copy; {{ now()->year }} Canopy Farm PH &mdash; Report generated on {{ now()->format('F d, Y h:i A') }}
    </footer>
</body>

</html>