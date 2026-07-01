@php
    $supplier = $item ?? null;
    $supplierStatus = data_get($supplier, 'status');
    $supplierStatus = $supplierStatus instanceof \App\Enums\RecordStatus ? $supplierStatus->value : ($supplierStatus ?? 'active');
@endphp

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Supplier Name</label>
        <input type="text" name="supplier_name" class="form-control @error('supplier_name') is-invalid @enderror" value="{{ old('supplier_name', data_get($supplier, 'supplier_name', '')) }}">
        @error('supplier_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', data_get($supplier, 'email', '')) }}">
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Phone</label>
        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', data_get($supplier, 'phone', '')) }}">
        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">GST Number</label>
        <input type="text" name="gst_number" class="form-control @error('gst_number') is-invalid @enderror" value="{{ old('gst_number', data_get($supplier, 'gst_number', '')) }}">
        @error('gst_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label class="form-label">Address</label>
        <textarea name="address" rows="3" class="form-control @error('address') is-invalid @enderror">{{ old('address', data_get($supplier, 'address', '')) }}</textarea>
        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Status</label>
        <select name="status" class="form-select @error('status') is-invalid @enderror">
            <option value="active" @selected(old('status', $supplierStatus) === 'active')>Active</option>
            <option value="inactive" @selected(old('status', $supplierStatus) === 'inactive')>Inactive</option>
        </select>
        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
