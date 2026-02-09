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
            text-align: center;
        }

        .qr {
            margin-top: 20px;
        }

        .qr img {
            width: 70px;
        }

        .note {
            font-size: 9px;
            margin-top: 10px;
        }

        .footer {
            position: absolute;
            bottom: 5px;
            width: 100%;
            font-size: 8px;
            color: #555;
        }
    </style>
</head>
<body>

<div class="card">

    <div class="qr">
        <img src="data:image/svg+xml;base64,{{ $qrCode }}">
    </div>

    <div class="note">
        Scan QR Code untuk verifikasi keanggotaan
    </div>

    <div class="footer">
        {{ config('institution.name') }}<br>
        {{ config('institution.address') }}
    </div>

</div>

</body>
</html>
