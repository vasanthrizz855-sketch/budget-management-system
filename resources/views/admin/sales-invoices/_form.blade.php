@include('admin.invoices.form', [
    'partyLabel' => 'Customer',
    'partyField' => 'customer_id',
    'partyNameField' => 'customer_name',
    'parties' => $customers,
    'products' => $products,
    'paymentStatuses' => $paymentStatuses,
    'item' => $item ?? null,
])

