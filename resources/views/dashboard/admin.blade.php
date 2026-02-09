@section('title', 'Dashboard Admin')

<x-app-layout>
    <x-slot name="header">
        <h2 class="h5 mb-0 d-flex align-items-center gap-2">
            <i class="bi bi-speedometer2"></i>
            Dashboard Admin
        </h2>
    </x-slot>

    <div class="py-2">
        {{-- <x-notification-panel /> --}}
        <div class="row g-3 mb-4">
            <x-dashboard-card title="Total Surat" :value="$totalSurat" color="primary" />
            <x-dashboard-card title="Bebas Pustaka" :value="$totalBebas" color="success" />
            <x-dashboard-card title="Penyerahan Skripsi" :value="$totalSkripsi" color="info" />
            <x-dashboard-card title="Hari Ini" :value="$hariIni" color="warning" />
        </div>

        <div class="row g-3">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white">
                        <div class="fw-semibold">Grafik Surat Per Bulan</div>
                    </div>
                    <div class="card-body" style="height: 320px;">
                        <canvas id="chartBulanan" aria-label="Grafik surat per bulan" role="img"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white">
                        <div class="fw-semibold">Komposisi Surat</div>
                    </div>
                    <div class="card-body" style="height: 320px;">
                        <canvas id="chartPie" aria-label="Komposisi surat" role="img"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (!window.Chart) {
                console.warn('Chart.js belum termuat.');
                return;
            }

            const chartBulanan = document.getElementById('chartBulanan');
            const chartPie = document.getElementById('chartPie');

            if (chartBulanan) {
                new Chart(chartBulanan, {
                    type: 'bar',
                    data: {
                        labels: @json($chartLabels),
                        datasets: [{
                            label: 'Jumlah Surat',
                            data: @json($chartValues),
                            backgroundColor: '#0d6efd'
                        }]
                    },
                    options: {
                        maintainAspectRatio: false
                    }
                });
            }

            if (chartPie) {
                new Chart(chartPie, {
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
                    },
                    options: {
                        maintainAspectRatio: false
                    }
                });
            }
        });
    </script>
@endpush
</x-app-layout>
