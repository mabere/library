<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan Digital - Selamat Datang</title>
    @include('pages.partials.public-head')
    <style>
        .logo-icon {
            font-size: 1.75rem;
        }

        /* Main Content */
        .main-content {
            padding: 4rem 0;
            flex: 1;
        }

        .title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        .subtitle {
            font-size: 1.125rem;
            line-height: 1.8;
            opacity: 0.8;
            margin-bottom: 2rem;
        }

        /* Features List */
        .features-list {
            list-style: none;
            margin-bottom: 2rem;
        }

        .feature-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1.5rem;
            gap: 1rem;
            padding-left: 1rem;
            position: relative;
        }

        .feature-item::before {
            content: "●";
            color: rgba(0, 0, 0, 0.3);
            position: absolute;
            left: 0;
            font-size: 1.5rem;
            line-height: 1;
        }

        body[data-bs-theme="dark"] .feature-item::before {
            color: rgba(255, 255, 255, 0.3);
        }

        .feature-link {
            color: var(--color-primary);
            text-decoration: none;
            font-weight: 600;
            border-bottom: 2px solid transparent;
            transition: all 0.2s ease;
        }

        .feature-link:hover {
            border-bottom-color: var(--color-primary);
            padding-bottom: 2px;
        }

        .feature-description {
            opacity: 0.7;
            font-size: 0.9rem;
            margin-top: 0.5rem;
            line-height: 1.5;
        }

        /* CTA Button */
        .btn-cta {
            background-color: var(--color-primary);
            color: #ffffff;
            padding: 0.75rem 2rem;
            border: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-cta:hover {
            background-color: var(--color-primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 61, 61, 0.4);
            color: #ffffff;
        }

        /* Right Section */
        .right-section {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 500px;
        }

        .library-decoration {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .library-icon {
            font-size: 12rem;
            color: var(--color-primary);
            opacity: 0.25;
            text-shadow: 0 0 40px var(--color-primary);
            animation: float 6s ease-in-out infinite;
            z-index: 2;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        .grid-pattern {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            opacity: 0.08;
            background-image:
                linear-gradient(90deg, var(--color-primary) 1px, transparent 1px),
                linear-gradient(0deg, var(--color-primary) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
        }

        /* Info Section */
        .info-section {
            padding: 4rem 0;
        }

        body[data-bs-theme="light"] .info-section {
            background-color: #f8f9fa;
        }

        body[data-bs-theme="dark"] .info-section {
            background-color: #16213e;
        }

        .section-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 3rem;
            text-align: center;
        }

        .info-card {
            border-left: 4px solid var(--color-primary);
            padding: 1.5rem;
            border-radius: 0.375rem;
            transition: all 0.3s ease;
            height: 100%;
        }

        body[data-bs-theme="light"] .info-card {
            background-color: #f3f4f6;
        }

        body[data-bs-theme="dark"] .info-card {
            background-color: rgba(255, 61, 61, 0.05);
        }

        .info-card:hover {
            transform: translateY(-4px);
        }

        body[data-bs-theme="light"] .info-card:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        body[data-bs-theme="dark"] .info-card:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }

        .card-title {
            color: var(--color-primary);
            margin-bottom: 0.5rem;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 700;
        }

        .card-description {
            opacity: 0.8;
            font-size: 0.9rem;
            line-height: 1.6;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .title {
                font-size: 2rem;
            }

            .library-icon {
                font-size: 8rem;
            }

            .right-section {
                min-height: 300px;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 2rem 0;
            }

            .title {
                font-size: 1.5rem;
            }

            .subtitle {
                font-size: 1rem;
            }

            .logo-text {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .title {
                font-size: 1.25rem;
            }

            .library-icon {
                font-size: 5rem;
            }

            .section-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body data-bs-theme="light">
    @include('pages.partials.public-nav', ['headerClass' => 'header fixed-top bg-white', 'navClass' => 'd-flex gap-3 align-items-center flex-wrap'])
<main class="main-content">
        <div class="container-lg">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="title">Selamat Datang di Perpustakaan Digital</h1>
                    <p class="subtitle">Temukan ribuan koleksi buku, jurnal, dan materi pembelajaran dalam satu platform yang mudah digunakan. Akses informasi kapan saja, di mana saja.</p>

                    <ul class="features-list">
                        <li class="feature-item">
                            <div>
                                Baca <a href="#fitur" class="feature-link">Fitur Lengkap</a>
                                <p class="feature-description">Jelajahi koleksi buku digital, e-journal, dan multimedia pembelajaran interaktif</p>
                            </div>
                        </li>

                        <li class="feature-item">
                            <div>
                                Hubungi <a href="#kontak" class="feature-link">Tim Kami</a>
                                <p class="feature-description">Dapatkan dukungan teknis dan bantuan dalam menggunakan layanan perpustakaan</p>
                            </div>
                        </li>

                        <li class="feature-item">
                            <div>
                                Pelajari <a href="#bantuan" class="feature-link">Panduan Pengguna</a>
                                <p class="feature-description">Tutorial lengkap dan FAQ untuk memaksimalkan penggunaan platform</p>
                            </div>
                        </li>
                    </ul>

                    <button class="btn btn-cta" onclick="document.getElementById('fitur').scrollIntoView({behavior: 'smooth'})">
                        Mulai Sekarang
                    </button>
                </div>

                <div class="col-lg-6">
                    <div class="right-section">
                        <div class="library-decoration">
                            <div class="grid-pattern"></div>
                            <div class="library-icon">📖</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <section class="info-section" id="fitur">
        <div class="container-lg">
            <h2 class="section-title">Fitur Unggulan</h2>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <article class="info-card">
                        <h3 class="card-title">🔍 Pencarian Cepat</h3>
                        <p class="card-description">Temukan buku dengan fitur pencarian canggih berdasarkan judul, penulis, atau kategori</p>
                    </article>
                </div>

                <div class="col-md-6 col-lg-4">
                    <article class="info-card">
                        <h3 class="card-title">⭐ Rekomendasi</h3>
                        <p class="card-description">Dapatkan rekomendasi koleksi berdasarkan preferensi dan riwayat membaca Anda</p>
                    </article>
                </div>

                <div class="col-md-6 col-lg-4">
                    <article class="info-card">
                        <h3 class="card-title">👥 Komunitas</h3>
                        <p class="card-description">Bergabunglah dengan komunitas pembaca dan bagikan ulasan buku favorit Anda</p>
                    </article>
                </div>

                <div class="col-md-6 col-lg-4">
                    <article class="info-card">
                        <h3 class="card-title">📱 Akses Multi-Device</h3>
                        <p class="card-description">Akses perpustakaan dari perangkat apa pun, kapan saja, dan di mana saja</p>
                    </article>
                </div>

                <div class="col-md-6 col-lg-4">
                    <article class="info-card">
                        <h3 class="card-title">💾 Koleksi Pribadi</h3>
                        <p class="card-description">Buat daftar buku favorit dan kelola riwayat peminjaman Anda</p>
                    </article>
                </div>

                <div class="col-md-6 col-lg-4">
                    <article class="info-card">
                        <h3 class="card-title">🔐 Aman & Terpercaya</h3>
                        <p class="card-description">Data pribadi Anda dilindungi dengan enkripsi tingkat enterprise</p>
                    </article>
                </div>
            </div>
        </div>
    </section>

    @include('pages.partials.public-scripts')
</body>
</html>

