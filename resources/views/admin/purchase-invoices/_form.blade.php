@include('admin.invoices.form', [
    'partyLabel' => 'Supplier',
    'partyField' => 'supplier_id',
    'partyNameField' => 'supplier_name',
    'parties' => $suppliers,
    'products' => $products,
    'paymentStatuses' => $paymentStatuses,
    'item' => $item ?? null,
])

