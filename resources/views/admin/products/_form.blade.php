@php
    $product = $item ?? null;
    $productStatus = data_get($product, 'status');
    $productStatus = $productStatus instanceof \App\Enums\RecordStatus ? $productStatus->value : ($productStatus ?? 'active');
@endphp

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Product Name</label>
        <input type="text" name="product_name" class="form-control @error('product_name') is-invalid @enderror" value="{{ old('product_name', data_get($product, 'product_name', '')) }}">
        @error('product_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Unit Price</label>
        <input type="number" step="0.01" name="unit_price" class="form-control @error('unit_price') is-invalid @enderror" value="{{ old('unit_price', data_get($product, 'unit_price', '')) }}">
        @error('unit_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Tax Percentage</label>
        <input type="number" step="0.01" name="tax_percentage" class="form-control @error('tax_percentage') is-invalid @enderror" value="{{ old('tax_percentage', data_get($product, 'tax_percentage', 0)) }}">
        @error('tax_percentage')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Status</label>
        <select name="status" class="form-select @error('status') is-invalid @enderror">
            <option value="active" @selected(old('status', $productStatus) === 'active')>Active</option>
            <option value="inactive" @selected(old('status', $productStatus) === 'inactive')>Inactive</option>
        </select>
        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label class="form-label">Description</label>
        <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', data_get($product, 'description', '')) }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
