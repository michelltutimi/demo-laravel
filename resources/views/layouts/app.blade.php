<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Metarugos')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* Pure White Background & Base Reset */
        body {
            background-color: #ffffff;
            color: #262626;
            margin: 0;
            padding: 0;
        }

        /* Desktop Main Content Container Offset */
        @media (min-width: 768px) {
            .main-content {
                margin-left: 72px; /* Fixed space for the collapsed icon column */
                transition: margin-left 0.3s ease;
            }
        }

        /* Mobile Bottom Nav Offset */
        @media (max-width: 767px) {
            .main-content {
                margin-bottom: 60px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Sidebar / Bottom Nav Component -->
    <x-navbar />

    <!-- Main View Content Injection -->
    <main class="main-content min-vh-100">
        @yield('content')
    </main>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>