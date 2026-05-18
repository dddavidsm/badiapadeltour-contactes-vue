<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Badia Padel Tour')</title>
    <link rel="icon" href="/favicon.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex: 1;
        }
        /* Primary color electric lime */
        .btn-primary, .bg-primary, .text-primary, .link-primary { color: #fff; background-color: #C9FF00; border-color: #C9FF00; }
        .btn-primary:hover { background-color: #B8EE00; border-color: #B8EE00; }
        .badge.bg-primary { background-color: #C9FF00; color: #111111; }
        /* Override Bootstrap "success" to use primary blue */
        .text-success { color: #000bff !important; }
        .btn-success { background-color: #000bff !important; border-color: #000bff !important; color: #fff !important; }
        .btn-success:hover, .btn-success:focus { background-color: #0008cc !important; border-color: #0008cc !important; }
        .bg-success { background-color: #000bff !important; }
        .badge.bg-success { background-color: #000bff !important; }
        a.text-success { color: #000bff !important; }
        /* Hover effects - electric lime */
        .hover-shadow { transition: all 0.3s ease; }
        .hover-shadow:hover { transform: translateY(-4px); box-shadow: 0 8px 16px rgba(201, 255, 0, 0.2) !important; }
        .card:hover { border-color: #C9FF00 !important; }
    </style>

    @yield('styles')
</head>
<body class="bg-light">

    @include('components.header')

    <main>
        @yield('content')
    </main>

    @include('components.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
