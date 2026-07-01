<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $item->invoice_no }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #fff; }
        .soft-card { box-shadow: none; border: 0; }
    </style>
</head>
<body class="p-4" onload="window.print()">
@include('admin.invoices.document', [
    'invoice' => $item,
    'title' => 'Sales Invoice',
    'partyLabel' => 'Customer',
    'partyName' => $item->customer?->customer_name,
])
</body>
</html>

