<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | Sistem Perpustakaan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background:#f4f6f9">


<div class="container mt-4">

    <h4 class="mb-4">📊 Dashboard</h4>

    {{-- STATISTIK --}}
    <div class="row g-3 mb-4">
        <x-dashboard-card title="Total Surat" :value="$totalSurat" color="primary" />
        <x-dashboard-card title="Bebas Pustaka" :value="$totalBebas" color="success" />
        <x-dashboard-card title="Penyerahan Skripsi" :value="$totalSkripsi" color="info" />
        <x-dashboard-card title="Hari Ini" :value="$hariIni" color="warning" />
    </div>

    {{-- GRAFIK --}}
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    📈 Grafik Surat Per Bulan
                </div>
                <div class="card-body">
                    <canvas id="chartBulanan"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    📊 Komposisi Surat
                </div>
                <div class="card-body">
                    <canvas id="chartPie"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // BAR CHART
    new Chart(document.getElementById('chartBulanan'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: 'Jumlah Surat',
                data: {!! json_encode($chartValues) !!},
                backgroundColor: '#0d6efd'
            }]
        }
    });

    // PIE CHART
    new Chart(document.getElementById('chartPie'), {
        type: 'pie',
        data: {
            labels: ['Bebas Pustaka', 'Penyerahan Skripsi'],
            datasets: [{
                data: [
                    {{ $pieData['bebas'] }},
                    {{ $pieData['skripsi'] }}
                ],
                backgroundColor: ['#198754', '#0dcaf0']
            }]
        }
    });
</script>

</body>
</html>
