<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<style>
    :root {
        --color-primary: #ff3d3d;
        --color-primary-hover: #ff5252;
        --color-primary-light: rgba(255, 61, 61, 0.1);
    }

    body {
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    body[data-bs-theme="light"] {
        background-color: #ffffff;
        color: #1a1a2e;
    }

    body[data-bs-theme="dark"] {
        background-color: #1a1a2e;
        color: #ffffff;
    }

    .header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        padding: 1rem 0;
        position: sticky;
        top: 0;
        z-index: 100;
    }

    body[data-bs-theme="dark"] .header {
        border-bottom-color: rgba(255, 255, 255, 0.1);
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

    .nav-link {
        color: inherit;
        text-decoration: none;
        opacity: 0.85;
        transition: color 0.3s ease, opacity 0.3s ease;
    }

    .nav-link:hover {
        color: var(--color-primary);
        opacity: 1;
    }

    .btn-login {
        color: inherit;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .btn-login:hover {
        color: var(--color-primary);
    }

    .btn-register {
        border: 2px solid currentColor;
        color: inherit;
        transition: all 0.3s ease;
    }

    .btn-register:hover {
        border-color: var(--color-primary);
        color: var(--color-primary);
    }

    .btn-theme {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        transition: all 0.3s ease;
        position: relative;
        border: none;
    }

    body[data-bs-theme="light"] .btn-theme {
        background-color: #f8f9fa;
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
</style>
