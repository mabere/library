<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Perpustakaan Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --color-primary: #ff3d3d;
            --color-primary-hover: #ff5252;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        body[data-bs-theme="light"] {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            color: #1a1a2e;
        }

        body[data-bs-theme="dark"] {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: #ffffff;
        }

        /* Header */
        .header {
            padding: 1rem 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        body[data-bs-theme="dark"] .header {
            border-bottom-color: rgba(255, 255, 255, 0.1);
            background: rgba(0, 0, 0, 0.3);
        }

        body[data-bs-theme="light"] .header {
            background: rgba(255, 255, 255, 0.8);
        }

        .logo {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--color-primary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: transform 0.2s ease;
        }

        .logo:hover {
            transform: translateY(-2px);
            color: var(--color-primary);
        }

        .header .nav-link {
            color: inherit;
            text-decoration: none;
            opacity: 0.85;
        }

        .header .nav-link:hover {
            color: var(--color-primary);
            opacity: 1;
        }

        .btn-theme {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            position: relative;
            border: none;
        }

        body[data-bs-theme="light"] .btn-theme {
            background-color: #f0f0f0;
            color: #1a1a2e;
        }

        body[data-bs-theme="dark"] .btn-theme {
            background-color: rgba(255, 61, 61, 0.1);
            color: #ffffff;
        }

        .btn-theme:hover {
            transform: scale(1.1);
        }

        .icon-sun,
        .icon-moon {
            position: absolute;
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        body[data-bs-theme="light"] .icon-sun {
            opacity: 1;
            transform: scale(1);
        }

        body[data-bs-theme="light"] .icon-moon {
            opacity: 0;
            transform: scale(0.5);
        }

        body[data-bs-theme="dark"] .icon-sun {
            opacity: 0;
            transform: scale(0.5);
        }

        body[data-bs-theme="dark"] .icon-moon {
            opacity: 1;
            transform: scale(1);
        }

        /* Main Content */
        .main-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }

        .auth-container {
            width: 100%;
            max-width: 500px;
        }

        .auth-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        body[data-bs-theme="light"] .auth-card {
            background: rgba(255, 255, 255, 0.95);
        }

        body[data-bs-theme="dark"] .auth-card {
            background: rgba(22, 33, 62, 0.95);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .auth-card:hover {
            box-shadow: 0 15px 40px rgba(255, 61, 61, 0.15);
        }

        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid var(--color-primary);
        }

        .auth-header h2 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .auth-header p {
            opacity: 0.8;
            margin: 0;
        }

        /* Form Styles */
        .form-label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            opacity: 0.9;
        }

        .form-control,
        .form-select {
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        body[data-bs-theme="dark"] .form-control,
        body[data-bs-theme="dark"] .form-select {
            background-color: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.1);
            color: #ffffff;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 0.2rem rgba(255, 61, 61, 0.25);
        }

        body[data-bs-theme="dark"] .form-control:focus,
        body[data-bs-theme="dark"] .form-select:focus {
            background-color: rgba(255, 255, 255, 0.08);
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        /* Error Messages */
        .invalid-feedback,
        .error-message {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: block;
        }

        .form-control.is-invalid,
        .form-select.is-invalid {
            border-color: #dc3545;
        }

        /* Buttons */
        .btn-primary {
            background-color: var(--color-primary);
            border-color: var(--color-primary);
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: var(--color-primary-hover);
            border-color: var(--color-primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 61, 61, 0.4);
        }

        .btn-primary:focus {
            box-shadow: 0 0 0 0.25rem rgba(255, 61, 61, 0.5);
        }

        /* Links */
        .link-primary {
            color: var(--color-primary);
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .link-primary:hover {
            color: var(--color-primary-hover);
            text-decoration: underline;
        }

        /* Auth Footer */
        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
        }

        body[data-bs-theme="dark"] .auth-footer {
            border-top-color: rgba(255, 255, 255, 0.1);
        }

        .auth-footer p {
            margin: 0;
            opacity: 0.8;
            font-size: 0.95rem;
        }

        /* Progress Indicator */
        .progress-step {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
            gap: 0.5rem;
            font-size: 0.85rem;
            opacity: 0.8;
        }

        .progress-step.active {
            opacity: 1;
            color: var(--color-primary);
        }

        /* Responsive */
        @media (max-width: 480px) {
            .auth-container {
                max-width: 100%;
            }

            .auth-card {
                border-radius: 0.75rem;
            }

            .main-content {
                padding: 1rem;
            }

            .auth-header h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body data-bs-theme="light">
    <!-- Header -->
    @include('pages.partials.public-nav')
<!-- Main Content -->
    <main class="main-content">
        <div class="auth-container">
            <div class="card auth-card">
                <div class="card-body p-4">
                    <div class="auth-header">
                        <h2>Daftar</h2>
                        <p>Buat akun perpustakaan Anda</p>
                    </div>

                    <!-- Register Form -->
                    <form method="POST" action="{{ route('register') }}" novalidate>
                        @csrf

                        <!-- Name -->
                        <div class="form-group">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input
                                id="name"
                                type="text"
                                name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}"
                                required
                                autofocus
                                autocomplete="name"
                                placeholder="Masukkan nama lengkap Anda"
                            >
                            @error('name')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- NIM -->
                        <div class="form-group">
                            <label for="nim" class="form-label">NIM</label>
                            <input
                                id="nim"
                                type="text"
                                name="nim"
                                class="form-control @error('nim') is-invalid @enderror"
                                value="{{ old('nim') }}"
                                required
                                placeholder="Masukkan nomor induk mahasiswa"
                            >
                            @error('nim')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Department -->
                        <div class="form-group">
                            <label for="department_id" class="form-label">Program Studi</label>
                            <select
                                id="department_id"
                                name="department_id"
                                class="form-select @error('department_id') is-invalid @enderror"
                                required
                            >
                                <option value="">-- Pilih Program Studi --</option>
                                @foreach ($departments as $department)
                                    <option
                                        value="{{ $department->id }}"
                                        @selected((string) old('department_id') === (string) $department->id)
                                    >
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email Address -->
                        <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}"
                                required
                                autocomplete="username"
                                placeholder="Masukkan email Anda"
                            >
                            @error('email')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="form-group">
                            <label for="password" class="form-label">Password</label>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                required
                                autocomplete="new-password"
                                placeholder="Minimal 8 karakter"
                            >
                            @error('password')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                class="form-control"
                                required
                                autocomplete="new-password"
                                placeholder="Ulangi password Anda"
                            >
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="bi bi-person-plus me-2"></i>
                            Daftar
                        </button>
                    </form>

                    <!-- Links -->
                    <div class="auth-footer">
                        <p>
                            Sudah punya akun?
                            <a href="{{ route('login') }}" class="link-primary fw-bold">
                                Masuk di sini
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        class ThemeManager {
            constructor() {
                this.STORAGE_KEY = 'theme-preference';
                this.DARK_THEME = 'dark';
                this.LIGHT_THEME = 'light';
                this.themeToggleBtn = document.getElementById('themeToggle');
                this.bodyElement = document.body;
                this.init();
            }

            init() {
                this.setTheme(this.getPreferredTheme());

                if (this.themeToggleBtn) {
                    this.themeToggleBtn.addEventListener('click', () => this.toggleTheme());
                }

                if (window.matchMedia) {
                    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                        if (!this.hasUserPreference()) {
                            this.setTheme(e.matches ? this.DARK_THEME : this.LIGHT_THEME);
                        }
                    });
                }
            }

            getPreferredTheme() {
                const userPreference = this.getUserPreference();
                return userPreference || this.getSystemPreference();
            }

            getUserPreference() {
                return localStorage.getItem(this.STORAGE_KEY);
            }

            hasUserPreference() {
                return localStorage.getItem(this.STORAGE_KEY) !== null;
            }

            getSystemPreference() {
                return (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches)
                    ? this.DARK_THEME
                    : this.LIGHT_THEME;
            }

            setTheme(theme) {
                if (![this.DARK_THEME, this.LIGHT_THEME].includes(theme)) {
                    theme = this.LIGHT_THEME;
                }

                this.bodyElement.setAttribute('data-bs-theme', theme);
                localStorage.setItem(this.STORAGE_KEY, theme);
                this.updateMetaThemeColor(theme);
            }

            toggleTheme() {
                const currentTheme = this.bodyElement.getAttribute('data-bs-theme') || this.LIGHT_THEME;
                const newTheme = currentTheme === this.LIGHT_THEME ? this.DARK_THEME : this.LIGHT_THEME;
                this.setTheme(newTheme);
            }

            updateMetaThemeColor(theme) {
                let metaThemeColor = document.querySelector('meta[name="theme-color"]');

                if (!metaThemeColor) {
                    metaThemeColor = document.createElement('meta');
                    metaThemeColor.setAttribute('name', 'theme-color');
                    document.head.appendChild(metaThemeColor);
                }

                metaThemeColor.setAttribute(
                    'content',
                    theme === this.DARK_THEME ? '#1a1a2e' : '#ffffff'
                );
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            new ThemeManager();
        });
    </script>
</body>
</html>

