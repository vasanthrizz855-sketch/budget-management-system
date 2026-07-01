<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-bg: #0f172a;
            --sidebar-accent: #1e293b;
            --brand: #2563eb;
            --brand-soft: rgba(37, 99, 235, 0.12);
            --page-bg: #f5f7fb;
        }

        body {
            background: var(--page-bg);
            min-height: 100vh;
        }

        .app-shell {
            display: flex;
            min-height: 100vh;
        }

        .app-sidebar {
            width: 270px;
            background: linear-gradient(180deg, var(--sidebar-bg), #111827);
            color: #fff;
            position: fixed;
            inset: 0 auto 0 0;
            overflow-y: auto;
            z-index: 1030;
        }

        .app-content {
            margin-left: 270px;
            width: calc(100% - 270px);
        }

        .sidebar-brand {
            font-weight: 700;
            letter-spacing: .4px;
        }

        .sidebar-link {
            color: rgba(255,255,255,.78);
            border-radius: 12px;
            margin-bottom: .25rem;
            padding: .8rem 1rem;
            display: flex;
            align-items: center;
            gap: .65rem;
            text-decoration: none;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            background: var(--brand-soft);
            color: #fff;
        }

        .topbar {
            backdrop-filter: blur(16px);
            background: rgba(245, 247, 251, .88);
        }

        .stat-card {
            border: 0;
            box-shadow: 0 10px 30px rgba(15, 23, 42, .08);
        }

        .soft-card {
            border: 0;
            border-radius: 18px;
            box-shadow: 0 12px 32px rgba(15, 23, 42, .08);
        }

        .table thead th {
            background: #eff4ff;
            font-weight: 600;
        }

        .spinner-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, .25);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2000;
        }

        @media (max-width: 991.98px) {
            .app-sidebar {
                transform: translateX(-100%);
                transition: transform .3s ease;
            }

            .app-sidebar.show {
                transform: translateX(0);
            }

            .app-content {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
<div id="pageSpinner" class="spinner-overlay d-none">
    <div class="spinner-border text-light" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<div class="app-shell">
    <aside class="app-sidebar p-3">
        @include('layouts.partials.leftmenu')
    </aside>

    <div class="app-content">
        @include('layouts.partials.header')

        <main class="container-fluid py-4">
            @yield('content')
        </main>
    </div>
</div>

@include('layouts.partials.toasts')

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('form').forEach(function (form) {
            form.addEventListener('submit', function () {
                document.getElementById('pageSpinner')?.classList.remove('d-none');
            });
        });

        document.querySelectorAll('.datatable').forEach(function (table) {
            if (! $.fn.DataTable.isDataTable(table)) {
                $(table).DataTable({
                    pageLength: 10,
                    responsive: true,
                });
            }
        });
    });
</script>
@stack('scripts')
</body>
</html>
