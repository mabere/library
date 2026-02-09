
@section('title', 'Laporan Pengunjung')
<x-app-layout>

<div class="container-fluid py-4">
    @php
        $routeName = request()->route()?->getName() ?? '';
        $exportRoute = str_starts_with($routeName, 'admin.')
            ? route('admin.visitors.export', request()->query())
            : route('staf.visitors.export', request()->query());
    @endphp

    @php
        $routeName = request()->route()?->getName() ?? '';
        $isAdminRoute = str_starts_with($routeName, 'admin.');
        $exportRoute = $isAdminRoute
            ? route('admin.visitors.export', request()->query())
            : route('staf.visitors.export', request()->query());
        $exportPdfRoute = $isAdminRoute
            ? route('admin.visitors.export_pdf', request()->query())
            : route('staf.visitors.export_pdf', request()->query());
    @endphp

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Laporan Pengunjung</h3>
        <div class="d-flex gap-2">
            <a href="{{ $exportPdfRoute }}" class="btn btn-outline-secondary btn-sm">
                Export PDF
            </a>
            <a href="{{ $exportRoute }}" class="btn btn-outline-primary btn-sm">
                Export CSV
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted">Total Pengunjung</div>
                    <div class="fs-3 fw-bold">{{ $total }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted">Hari Ini</div>
                    <div class="fs-3 fw-bold">{{ $today }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted">Bulan Ini</div>
                    <div class="fs-3 fw-bold">{{ $month }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="mb-3">Kunjungan 7 Hari Terakhir</h5>
                    <div style="height: 280px;">
                        <canvas id="visitorsDailyChart" aria-label="Kunjungan harian" role="img"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Kunjungan Mingguan / Bulanan</h5>
                        <select id="visitorsRangeSelect" class="form-select form-select-sm" style="max-width: 220px;">
                            <option value="weekly">Mingguan (4 minggu)</option>
                            <option value="monthly">Bulanan (6 bulan)</option>
                        </select>
                    </div>
                    <div style="height: 280px;">
                        <canvas id="visitorsRangeChart" aria-label="Kunjungan mingguan/bulanan" role="img"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <div class="card mb-4">
        <div class="card-body">
            <form class="row g-3" method="GET">
                <div class="col-md-4">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="from" class="form-control" value="{{ $from }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="to" class="form-control" value="{{ $to }}">
                </div>
                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button class="btn btn-primary" type="submit">Filter</button>
                    <a class="btn btn-outline-secondary" href="{{ request()->url() }}">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="mb-3">Statistik Per Prodi</h5>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Program Studi</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($byDepartment as $row)
                            <tr>
                                <td>{{ optional($row->department)->name ?? 'Tanpa Prodi' }}</td>
                                <td>{{ $row->total }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-muted">Belum ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="mb-3">Daftar Kunjungan</h5>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Nama</th>
                            <th>NIM</th>
                            <th>Program Studi/Institusi</th>
                            <th>Keperluan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($visitors as $visitor)
                            <tr>
                                <td>{{ $visitor->visit_at?->format('Y-m-d H:i') }}</td>
                                <td>{{ $visitor->name }}</td>
                                <td>{{ $visitor->nim ?? '-' }}</td>
                                {{-- <td>{{ optional($visitor->department)->name ?? '-' }}</td> --}}
                                <td>
                                    @if ($visitor->visitor_type === 'mahasiswa')
                                        {{ optional($visitor->department)->name ?? '-' }}
                                    @else
                                        {{ ($visitor->institution) ?? '-' }}
                                    @endif
                                </td>
                                <td>{{ implode(', ', $visitor->purpose ?? []) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-muted">Belum ada data kunjungan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $visitors->links() }}
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

            const canvas = document.getElementById('visitorsDailyChart');
            if (!canvas) return;

            new Chart(canvas, {
                type: 'line',
                data: {
                    labels: @json($dailyLabels),
                    datasets: [{
                        label: 'Jumlah Kunjungan',
                        data: @json($dailyValues),
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13, 110, 253, 0.15)',
                        tension: 0.35,
                        fill: true
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0 }
                        }
                    }
                }
            });

            const rangeCanvas = document.getElementById('visitorsRangeChart');
            const rangeSelect = document.getElementById('visitorsRangeSelect');
            if (!rangeCanvas || !rangeSelect) return;

            const weeklyData = {
                labels: @json($weeklyLabels),
                values: @json($weeklyValues),
            };
            const monthlyData = {
                labels: @json($monthlyLabels),
                values: @json($monthlyValues),
            };

            const rangeChart = new Chart(rangeCanvas, {
                type: 'bar',
                data: {
                    labels: weeklyData.labels,
                    datasets: [{
                        label: 'Jumlah Kunjungan',
                        data: weeklyData.values,
                        backgroundColor: '#198754'
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0 }
                        }
                    }
                }
            });

            rangeSelect.addEventListener('change', (e) => {
                const mode = e.target.value;
                if (mode === 'monthly') {
                    rangeChart.data.labels = monthlyData.labels;
                    rangeChart.data.datasets[0].data = monthlyData.values;
                    rangeChart.data.datasets[0].backgroundColor = '#0dcaf0';
                } else {
                    rangeChart.data.labels = weeklyData.labels;
                    rangeChart.data.datasets[0].data = weeklyData.values;
                    rangeChart.data.datasets[0].backgroundColor = '#198754';
                }
                rangeChart.update();
            });
        });
    </script>
@endpush
</x-app-layout>
