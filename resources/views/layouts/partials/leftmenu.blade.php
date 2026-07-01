<div class="mb-4">
    <div class="sidebar-brand fs-5">{{ config('app.name') }}</div>
    <div class="text-white-50 small">Admin Panel</div>
</div>

<nav class="nav flex-column">
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ nav_active('admin.dashboard') }}">
        <span>Dashboard</span>
    </a>
    <a href="{{ route('admin.customers.index') }}" class="sidebar-link {{ nav_active('admin.customers.*') }}">
        <span>Customers</span>
    </a>
    <a href="{{ route('admin.suppliers.index') }}" class="sidebar-link {{ nav_active('admin.suppliers.*') }}">
        <span>Suppliers</span>
    </a>
    <a href="{{ route('admin.products.index') }}" class="sidebar-link {{ nav_active('admin.products.*') }}">
        <span>Products</span>
    </a>
    <a href="{{ route('admin.purchase-invoices.index') }}" class="sidebar-link {{ nav_active('admin.purchase-invoices.*') }}">
        <span>Purchase Invoices</span>
    </a>
    <a href="{{ route('admin.sales-invoices.index') }}" class="sidebar-link {{ nav_active('admin.sales-invoices.*') }}">
        <span>Sales Invoices</span>
    </a>
    <a href="{{ route('admin.budgets.index') }}" class="sidebar-link {{ nav_active('admin.budgets.*') }}">
        <span>Budgets</span>
    </a>
    <a href="{{ route('admin.expenses.index') }}" class="sidebar-link {{ nav_active('admin.expenses.*') }}">
        <span>Expenses</span>
    </a>
    <a href="{{ route('admin.payments.index') }}" class="sidebar-link {{ nav_active('admin.payments.*') }}">
        <span>Payments</span>
    </a>
    <a href="{{ route('admin.reports.index') }}" class="sidebar-link {{ nav_active('admin.reports.*') }}">
        <span>Reports</span>
    </a>
    <a href="{{ route('profile.edit') }}" class="sidebar-link {{ nav_active('profile.*') }}">
        <span>Profile</span>
    </a>
</nav>
