<nav class="navbar navbar-expand-lg topbar border-bottom sticky-top">
    <div class="container-fluid px-3">
        <button class="btn btn-outline-primary btn-sm d-lg-none me-2" type="button" id="sidebarToggle" aria-label="Toggle sidebar">
            <span class="fs-4 lh-1">&#9776;</span>
        </button>

        <div class="d-flex align-items-center gap-3">
            <div>
                <div class="fw-semibold">{{ config('app.name') }}</div>
                <small class="text-muted">Financial operations dashboard</small>
            </div>
        </div>

        <div class="ms-auto d-flex align-items-center gap-2">
            <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary btn-sm">Profile</a>
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm">Logout</button>
            </form>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggle = document.getElementById('sidebarToggle');
        const sidebar = document.querySelector('.app-sidebar');
        if (toggle && sidebar) {
            toggle.addEventListener('click', function () {
                sidebar.classList.toggle('show');
            });
        }
    });
</script>
