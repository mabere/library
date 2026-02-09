<!DOCTYPE html>
<html>
<head>
    <style>
        @page { margin: 0; }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .page {
            width: 242px;
            height: 153px;
            page-break-after: always;
        }

        .card {
            width: 242px;
            height: 153px;
            border: 1px solid #000;
            border-radius: 8px;
            padding: 8px;
            box-sizing: border-box;
            position: relative;
        }

        /* === FRONT === */
        .header {
            text-align: center;
            border-bottom: 1px solid #000;
            font-size: 11px;
            font-weight: bold;
            padding-bottom: 4px;
        }

        .content {
            display: flex;
            gap: 8px;
            margin-top: 6px;
            font-size: 10px;
        }

        .photo {
            width: 60px;
            height: 80px;
            border: 1px solid #000;
            text-align: center;
            line-height: 80px;
            font-size: 9px;
        }

        .footer {
            position: absolute;
            bottom: 5px;
            left: 8px;
            font-size: 8px;
        }

        /* === BACK === */
        .qr {
            margin-top: 30px;
            text-align: center;
        }

        .qr img {
            width: 70px;
        }

        .note {
            margin-top: 10px;
            font-size: 9px;
            text-align: center;
        }
    </style>
</head>
<body>

{{-- =======================
    HALAMAN DEPAN
======================= --}}
<div class="page">
    <div class="card">

        <div class="header">
            {{ config('institution.name') }}<br>
            KARTU PERPUSTAKAAN
        </div>

        <div class="content">
            <div class="photo">
                @if($student->photo)
                    <img src="{{ public_path('storage/'.$student->photo) }}"
                         width="60" height="80">
                @else
                    FOTO
                @endif
            </div>

            <div>
                <table>
                    <tr><td>NIM</td><td>: {{ $student->nim }}</td></tr>
                    <tr><td>Nama</td><td>: {{ $student->nama }}</td></tr>
                    <tr><td>Prodi</td><td>: {{ $student->department?->name ?? '-' }}</td></tr>
                    <tr><td>Status</td><td>: AKTIF</td></tr>
                </table>
            </div>
        </div>

        <div class="footer">
            Berlaku selama terdaftar sebagai mahasiswa
        </div>

    </div>
</div>

{{-- =======================
    HALAMAN BELAKANG
======================= --}}
<div class="page">
    <div class="card">

        <div class="qr">
            <img src="data:image/svg+xml;base64,{{ $qrCode }}">
        </div>

        <div class="note">
            Scan QR Code untuk verifikasi keanggotaan<br>
            {{ config('institution.name') }}
        </div>

        <div class="footer">
            {{ config('institution.address') }}
        </div>

    </div>
</div>

</body>
</html>
