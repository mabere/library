<!DOCTYPE html>
<html>
<head>
    <style>
        @page { margin: 0; }
        body { margin: 0; font-family: Arial, sans-serif; }

        .card {
            width: 242px;
            height: 153px;
            border: 1px solid #000;
            border-radius: 8px;
            padding: 8px;
            box-sizing: border-box;
            position: relative;
        }

        .header {
            text-align: center;
            border-bottom: 1px solid #000;
            padding-bottom: 4px;
            font-size: 11px;
            font-weight: bold;
        }

        .content {
            margin-top: 6px;
            display: flex;
            gap: 8px;
        }

        .photo {
            width: 60px;
            height: 80px;
            border: 1px solid #000;
            text-align: center;
            font-size: 9px;
            line-height: 80px;
        }

        .info {
            font-size: 10px;
        }

        .info table {
            width: 100%;
        }

        .info td {
            padding: 1px 0;
        }

        .footer {
            position: absolute;
            bottom: 5px;
            left: 8px;
            font-size: 8px;
            color: #555;
        }
    </style>
</head>
<body>

<div class="card">

    <div class="header">
        {{ config('institution.name') }}<br>
        KARTU PERPUSTAKAAN
    </div>

    <div class="content">
        <div class="photo">
            @if($student->photo)
                <img src="{{ public_path('storage/'.$student->photo) }}" width="60" height="80">
            @else
                FOTO
            @endif
        </div>

        <div class="info">
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

</body>
</html>
