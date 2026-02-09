<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Contact</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @include('pages.partials.public-head')
</head>

<body>
@include('pages.partials.public-nav')
<div class="container mt-5">
    <h3 class="mb-3">Contact</h3>

    <div class="card">
        <div class="card-body">
            <p class="mb-2">
                Silakan hubungi kami untuk bantuan teknis atau pertanyaan layanan perpustakaan.
            </p>

            <table class="table table-bordered mb-0">
                <tr>
                    <th width="30%">Email</th>
                    <td>admin@fkip-unilaki.ac.id</td>
                </tr>
                <tr>
                    <th>Telepon</th>
                    <td>+62 812-xxxx-xxxx</td>
                </tr>
                <tr>
                    <th>Alamat</th>
                    <td>Kampus FKIP Unilaki, UPT Perpustakaan</td>
                </tr>
            </table>
        </div>
    </div>
</div>
@include('pages.partials.public-scripts')
</body>
</html>
