<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Sistem Perpustakaan' }}</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Custom Style --}}
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar-brand {
            font-weight: bold;
        }
        footer {
            font-size: 14px;
            color: #777;
        }
    </style>

    @stack('styles')
</head>
<body>

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                📚 Sistem Perpustakaan
            </a>
        </div>
    </nav>

    {{-- Content --}}
    <main class="container my-4">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer class="text-center mt-5 mb-3">
        <hr>
        <p>
            &copy; {{ date('Y') }} Perpustakaan Kampus
            <br>
            <small>Sistem Bebas Pustaka & Skripsi</small>
        </p>
    </footer>

    {{-- Script --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
