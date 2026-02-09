<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Perpustakaan Digital</title>
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
            max-width: 450px;
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

        /* Checkbox */
        .form-check {
            margin-top: 1rem;
            margin-bottom: 1.5rem;
        }

        .form-check-input {
            width: 1.25em;
            height: 1.25em;
            border: 2px solid rgba(0, 0, 0, 0.2);
            border-radius: 0.375rem;
            transition: all 0.3s ease;
        }

        body[data-bs-theme="dark"] .form-check-input {
            background-color: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .form-check-input:checked {
            background-color: var(--color-primary);
            border-color: var(--color-primary);
        }

        .form-check-label {
            margin-bottom: 0;
            margin-left: 0.5rem;
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

        /* Session Status */
        .alert {
            border-radius: 0.5rem;
            border: none;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        body[data-bs-theme="dark"] .alert-success {
            background-color: rgba(40, 167, 69, 0.1);
            color: #6fc381;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        body[data-bs-theme="dark"] .alert-danger {
            background-color: rgba(220, 53, 69, 0.1);
            color: #f8949a;
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
                        <h2>Masuk</h2>
                        <p>Akses akun perpustakaan Anda</p>
                    </div>

                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login') }}" novalidate>
                        @csrf

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
                                autofocus
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
                                autocomplete="current-password"
                                placeholder="Masukkan password Anda"
                            >
                            @error('password')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="form-check">
                            <input
                                id="remember_me"
                                type="checkbox"
                                class="form-check-input"
                                name="remember"
                            >
                            <label class="form-check-label" for="remember_me">
                                Ingat saya
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="bi bi-box-arrow-in-right me-2"></i>
                            Masuk
                        </button>
                    </form>

                    <!-- Links -->
                    <div class="auth-footer">
                        @if (Route::has('password.request'))
                            <p class="mb-3">
                                <a href="{{ route('password.request') }}" class="link-primary">
                                    Lupa password?
                                </a>
                            </p>
                        @endif

                        <p>
                            Belum punya akun?
                            <a href="{{ route('register') }}" class="link-primary fw-bold">
                                Daftar di sini
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

