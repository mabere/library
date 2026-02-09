<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tentang Aplikasi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @include('pages.partials.public-head')
</head>

<body>

@include('pages.partials.public-nav')

<div class="container mt-5">

    <h3 class="mb-3">📘 Tentang Aplikasi</h3>

    <div class="card">
        <div class="card-body">

            <p>
                <strong>Sistem Layanan Perpustakaan</strong> adalah aplikasi internal
                yang digunakan untuk mengelola:
            </p>

            <ul>
                <li>Surat Bebas Pustaka</li>
                <li>Penyerahan Skripsi Mahasiswa</li>
                <li>Verifikasi Keaslian Surat</li>
                <li>Arsip dan Rekap Administrasi</li>
            </ul>

            <p>
                Aplikasi ini dirancang untuk mempercepat pelayanan,
                mengurangi penggunaan kertas, dan meningkatkan akurasi data.
            </p>

            <hr>

            <table class="table table-bordered">
                <tr>
                    <th width="30%">Nama Aplikasi</th>
                    <td>Sistem Perpustakaan</td>
                </tr>
                <tr>
                    <th>Versi</th>
                    <td>1.0</td>
                </tr>
                <tr>
                    <th>Pengembang</th>
                    <td>Unit Perpustakaan</td>
                </tr>
                <tr>
                    <th>Tahun</th>
                    <td>{{ date('Y') }}</td>
                </tr>
            </table>

        </div>
    </div>

</div>
@include('pages.partials.public-scripts')

</body>
</html>
