<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bantuan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @include('pages.partials.public-head')
</head>

<body>

@include('pages.partials.public-nav')

<div class="container mt-5">

    <h3 class="mb-4">❓ Pusat Bantuan</h3>

    <div class="accordion" id="faq">

        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button" data-bs-toggle="collapse" data-bs-target="#q1">
                    Bagaimana cara membuat surat?
                </button>
            </h2>
            <div id="q1" class="accordion-collapse collapse show">
                <div class="accordion-body">
                    Masuk ke menu <b>Buat Surat</b>, isi data mahasiswa, lalu simpan.
                    Surat akan otomatis dibuat beserta QR Code.
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#q2">
                    Bagaimana cara verifikasi surat?
                </button>
            </h2>
            <div id="q2" class="accordion-collapse collapse">
                <div class="accordion-body">
                    Buka menu <b>Verifikasi QR</b>, unggah gambar QR Code dari surat,
                    lalu sistem akan menampilkan status surat.
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#q3">
                    Apa arti surat dibatalkan?
                </button>
            </h2>
            <div id="q3" class="accordion-collapse collapse">
                <div class="accordion-body">
                    Surat dibatalkan berarti surat tidak berlaku lagi dan tidak dapat digunakan.
                </div>
            </div>
        </div>

    </div>

</div>

@include('pages.partials.public-scripts')

</body>
</html>
