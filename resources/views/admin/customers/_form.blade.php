@php
    $customer = $item ?? null;
    $customerStatus = data_get($customer, 'status');
    $customerStatus = $customerStatus instanceof \App\Enums\RecordStatus ? $customerStatus->value : ($customerStatus ?? 'active');
@endphp

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Customer Name</label>
        <input type="text" name="customer_name" class="form-control @error('customer_name') is-invalid @enderror" value="{{ old('customer_name', data_get($customer, 'customer_name', '')) }}">
        @error('customer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', data_get($customer, 'email', '')) }}">
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Phone</label>
        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', data_get($customer, 'phone', '')) }}">
        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">GST Number</label>
        <input type="text" name="gst_number" class="form-control @error('gst_number') is-invalid @enderror" value="{{ old('gst_number', data_get($customer, 'gst_number', '')) }}">
        @error('gst_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label class="form-label">Address</label>
        <textarea name="address" rows="3" class="form-control @error('address') is-invalid @enderror">{{ old('address', data_get($customer, 'address', '')) }}</textarea>
        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Status</label>
        <select name="status" class="form-select @error('status') is-invalid @enderror">
            <option value="active" @selected(old('status', $customerStatus) === 'active')>Active</option>
            <option value="inactive" @selected(old('status', $customerStatus) === 'inactive')>Inactive</option>
        </select>
        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
