@php
    $invoice = $item ?? null;
    $partyNameField = $partyNameField ?? 'name';
    $invoiceItems = old('items', collect($invoice?->items ?? [])->map(fn ($row) => [
        'product_id' => $row->product_id,
        'quantity' => $row->quantity,
        'price' => $row->price,
        'tax' => $row->tax,
    ])->all());

    if (empty($invoiceItems)) {
        $invoiceItems = [[
            'product_id' => '',
            'quantity' => 1,
            'price' => '',
            'tax' => '',
        ]];
    }

    $currentParty = data_get($invoice, $partyField);
    $currentPartyId = is_object($currentParty) ? data_get($currentParty, 'id') : $currentParty;
@endphp

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">{{ $partyLabel }}</label>
        <select name="{{ $partyField }}" class="form-select @error($partyField) is-invalid @enderror">
            <option value="">Select {{ strtolower($partyLabel) }}</option>
            @foreach($parties as $party)
                <option value="{{ $party->id }}" @selected(old($partyField, $currentPartyId) == $party->id)>{{ data_get($party, $partyNameField, data_get($party, 'name')) }}</option>
            @endforeach
        </select>
        @error($partyField)<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
        <label class="form-label">Invoice Date</label>
        <input type="date" name="invoice_date" class="form-control @error('invoice_date') is-invalid @enderror" value="{{ old('invoice_date', optional(data_get($invoice, 'invoice_date'))->format('Y-m-d') ?? now()->format('Y-m-d')) }}">
        @error('invoice_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
        <label class="form-label">Payment Status</label>
        <select name="payment_status" class="form-select @error('payment_status') is-invalid @enderror">
            @foreach($paymentStatuses as $value => $label)
                <option value="{{ $value }}" @selected(old('payment_status', data_get($invoice, 'payment_status') instanceof \App\Enums\PaymentStatus ? data_get($invoice, 'payment_status')->value : data_get($invoice, 'payment_status', 'pending')) === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('payment_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Discount Amount</label>
        <input type="number" step="0.01" id="discount_amount" name="discount_amount" class="form-control @error('discount_amount') is-invalid @enderror" value="{{ old('discount_amount', data_get($invoice, 'discount_amount', 0)) }}">
        @error('discount_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="table-responsive mt-4">
    <table class="table align-middle" id="invoiceLinesTable">
        <thead>
        <tr>
            <th style="min-width: 220px;">Product</th>
            <th style="width: 110px;">Qty</th>
            <th style="width: 140px;">Price</th>
            <th style="width: 140px;">Tax</th>
            <th style="width: 140px;">Total</th>
            <th style="width: 90px;"></th>
        </tr>
        </thead>
        <tbody id="invoiceLinesBody">
        @foreach($invoiceItems as $index => $row)
            <tr class="invoice-line">
                <td>
                    <select name="items[{{ $index }}][product_id]" class="form-select product-select" required>
                        <option value="">Select product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}"
                                    data-price="{{ $product->unit_price }}"
                                    data-tax="{{ $product->tax_percentage }}"
                                    @selected((int) ($row['product_id'] ?? 0) === $product->id)>
                                {{ $product->product_name }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td><input type="number" step="0.01" min="0.01" name="items[{{ $index }}][quantity]" class="form-control qty-input" value="{{ $row['quantity'] ?? 1 }}" required></td>
                <td><input type="number" step="0.01" min="0" name="items[{{ $index }}][price]" class="form-control price-input" value="{{ $row['price'] ?? '' }}" required></td>
                <td><input type="number" step="0.01" min="0" name="items[{{ $index }}][tax]" class="form-control tax-input" value="{{ $row['tax'] ?? '' }}" readonly required></td>
                <td><input type="number" step="0.01" class="form-control total-input" value="" readonly></td>
                <td class="text-end">
                    <button type="button" class="btn btn-outline-danger btn-sm remove-line">Remove</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
    <button type="button" class="btn btn-outline-primary btn-sm" id="addInvoiceLine">Add More Products</button>
    <div class="text-end">
        <div>Subtotal: <strong id="subtotalAmount">{{ format_money(data_get($invoice, 'subtotal', 0)) }}</strong></div>
        <div>Tax: <strong id="taxAmount">{{ format_money(data_get($invoice, 'tax_amount', 0)) }}</strong></div>
        <div>Discount: <strong id="discountDisplay">{{ format_money(data_get($invoice, 'discount_amount', 0)) }}</strong></div>
        <div class="h5 mt-2">Grand Total: <span id="grandTotal">{{ format_money(data_get($invoice, 'grand_total', 0)) }}</span></div>
    </div>
</div>

<div class="mt-4">
    <label class="form-label">Remarks</label>
    <textarea name="remarks" rows="3" class="form-control @error('remarks') is-invalid @enderror">{{ old('remarks', data_get($invoice, 'remarks', '')) }}</textarea>
    @error('remarks')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

@push('scripts')
<script>
    (function () {
        const products = @json($products->map(fn ($product) => [
            'id' => $product->id,
            'name' => $product->product_name,
            'price' => (float) $product->unit_price,
            'tax' => (float) $product->tax_percentage,
        ])->values());
        const tbody = document.getElementById('invoiceLinesBody');
        const addButton = document.getElementById('addInvoiceLine');
        const discountInput = document.getElementById('discount_amount');
        let rowIndex = tbody.querySelectorAll('.invoice-line').length;

        function productOptions(selectedId = '') {
            let html = '<option value="">Select product</option>';
            products.forEach(product => {
                html += `<option value="${product.id}" data-price="${product.price}" data-tax="${product.tax}" ${String(product.id) === String(selectedId) ? 'selected' : ''}>${product.name}</option>`;
            });
            return html;
        }

        function applyProductDefaults(row, force = false) {
            const product = row.querySelector('.product-select').selectedOptions[0];
            const qty = parseFloat(row.querySelector('.qty-input').value || 0);
            const priceInput = row.querySelector('.price-input');
            const taxInput = row.querySelector('.tax-input');

            if (product && product.value) {
                const defaultPrice = parseFloat(product.dataset.price || 0);
                const taxRate = parseFloat(product.dataset.tax || 0);

                if (force || !priceInput.value) {
                    priceInput.value = defaultPrice.toFixed(2);
                }

                const price = parseFloat(priceInput.value || 0);
                taxInput.value = ((qty * price * taxRate) / 100).toFixed(2);
            }
        }

        function recalculateRow(row, autoFill = false, force = false) {
            if (autoFill) {
                applyProductDefaults(row, force);
            }

            const qty = parseFloat(row.querySelector('.qty-input').value || 0);
            const price = parseFloat(row.querySelector('.price-input').value || 0);
            const tax = parseFloat(row.querySelector('.tax-input').value || 0);
            const total = (qty * price) + tax;
            row.querySelector('.total-input').value = total.toFixed(2);
            recalculateTotals();
        }

        function recalculateTotals() {
            let subtotal = 0;
            let taxTotal = 0;

            tbody.querySelectorAll('.invoice-line').forEach(row => {
                const qty = parseFloat(row.querySelector('.qty-input').value || 0);
                const price = parseFloat(row.querySelector('.price-input').value || 0);
                const tax = parseFloat(row.querySelector('.tax-input').value || 0);

                subtotal += qty * price;
                taxTotal += tax;
            });

            const discount = parseFloat(discountInput.value || 0);
            const grandTotal = subtotal + taxTotal - discount;

            document.getElementById('subtotalAmount').textContent = 'INR ' + subtotal.toFixed(2);
            document.getElementById('taxAmount').textContent = 'INR ' + taxTotal.toFixed(2);
            document.getElementById('discountDisplay').textContent = 'INR ' + discount.toFixed(2);
            document.getElementById('grandTotal').textContent = 'INR ' + grandTotal.toFixed(2);
        }

        function bindRow(row) {
            row.querySelectorAll('.product-select, .qty-input, .price-input, .tax-input').forEach(input => {
                if (input.classList.contains('product-select')) {
                    input.addEventListener('change', function () {
                        recalculateRow(row, true, true);
                    });
                } else {
                    input.addEventListener('change', function () {
                        recalculateRow(row, true, false);
                    });
                    input.addEventListener('input', function () {
                        recalculateRow(row, true, false);
                    });
                }
            });

            row.querySelector('.remove-line')?.addEventListener('click', function () {
                if (tbody.querySelectorAll('.invoice-line').length > 1) {
                    row.remove();
                    recalculateTotals();
                }
            });

            recalculateRow(row, false, false);
        }

        addButton?.addEventListener('click', function () {
            const template = document.createElement('tr');
            template.className = 'invoice-line';
            template.innerHTML = `
                <td>
                    <select name="items[${rowIndex}][product_id]" class="form-select product-select" required>
                        ${productOptions()}
                    </select>
                </td>
                <td><input type="number" step="0.01" min="0.01" name="items[${rowIndex}][quantity]" class="form-control qty-input" value="1" required></td>
                <td><input type="number" step="0.01" min="0" name="items[${rowIndex}][price]" class="form-control price-input" value="0" required></td>
                <td><input type="number" step="0.01" min="0" name="items[${rowIndex}][tax]" class="form-control tax-input" value="0" readonly required></td>
                <td><input type="number" step="0.01" class="form-control total-input" value="0" readonly></td>
                <td class="text-end"><button type="button" class="btn btn-outline-danger btn-sm remove-line">Remove</button></td>
            `;
            tbody.appendChild(template);
            bindRow(template);
            rowIndex++;
        });

        discountInput?.addEventListener('input', recalculateTotals);

        tbody.querySelectorAll('.invoice-line').forEach(bindRow);
        recalculateTotals();
    })();
</script>
@endpush
