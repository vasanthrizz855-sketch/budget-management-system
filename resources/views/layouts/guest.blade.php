<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: radial-gradient(circle at top left, #dbeafe 0, #f8fafc 35%, #eef2ff 100%);
        }

        .auth-shell {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .auth-card {
            width: 100%;
            max-width: 480px;
            border: 0;
            border-radius: 22px;
            box-shadow: 0 20px 45px rgba(15, 23, 42, .12);
        }
    </style>
    @stack('styles')
</head>
<body>
<div class="auth-shell">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@include('layouts.partials.toasts')
@stack('scripts')
</body>
</html>
