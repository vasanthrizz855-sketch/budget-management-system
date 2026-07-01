<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $reportType }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111827; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #cbd5e1; padding: 8px; text-align: left; }
        th { background: #e2e8f0; }
        .summary { margin-top: 10px; }
    </style>
</head>
<body>
    <h2>{{ $reportType }}</h2>
    <div class="summary">
        <strong>Total Records:</strong> {{ $summary['count'] ?? 0 }}<br>
        <strong>Total Value:</strong> {{ number_format((float) ($summary['total'] ?? 0), 2) }}
    </div>

    <table>
        <thead>
        <tr>
            @foreach($headers as $header)
                <th>{{ $header }}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @forelse($rows as $row)
            <tr>
                @foreach(array_values($row) as $value)
                    <td>{{ is_numeric($value) ? number_format((float) $value, 2) : $value }}</td>
                @endforeach
            </tr>
        @empty
            <tr>
                <td colspan="{{ count($headers) }}">No records found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</body>
</html>

