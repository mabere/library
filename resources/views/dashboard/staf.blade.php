@section('title', 'Dashboard Staf')

<x-app-layout>
    <x-slot name="header">
        <h2 class="h5 mb-0 d-flex align-items-center gap-2">
            <i class="bi bi-person-workspace"></i>
            Dashboard Staf
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="container">
            <x-notification-panel />

            {{-- ================= STATISTIK UTAMA ================= --}}
            <div class="row g-3 mb-4 text-center">
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">
                            <div class="text-muted small">Menunggu Verifikasi</div>
                            <div class="fs-3 fw-bold text-warning">
                                {{ $menungguVerifikasi }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">
                            <div class="text-muted small">Verifikasi Hari Ini</div>
                            <div class="fs-3 fw-bold text-primary">
                                {{ $verifikasiHariIni }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">
                            <div class="text-muted small">Ditolak Total</div>
                            <div class="fs-3 fw-bold text-danger">
                                {{ $ditolakTotal }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ================= ACTION ================= --}}
            <div class="d-flex justify-content-end mb-4">
                <a href="{{ route('staf.bebas_pustaka.index') }}"
                   class="btn btn-dark">
                    <i class="bi bi-clipboard-check"></i>
                    Kelola Verifikasi
                </a>
            </div>

            {{-- ================= RECENT SUBMISSIONS ================= --}}
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">

                    <h5 class="fw-semibold mb-3">
                        Pengajuan Terbaru
                    </h5>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>NIM</th>
                                    <th>Nama</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recent as $req)
                                    <tr>
                                        <td>
                                            {{ optional($req->submitted_at)->format('d-m-Y H:i') ?? '-' }}
                                        </td>
                                        <td>{{ $req->nim }}</td>
                                        <td>{{ $req->nama }}</td>
                                        <td>
                                            <span class="badge
                                                @if($req->status === 'diverifikasi_staf') text-bg-warning
                                                @elseif($req->status === 'ditolak_staf') text-bg-danger
                                                @elseif($req->status === 'disetujui_kepala') text-bg-success
                                                @else text-bg-secondary
                                                @endif">
                                                {{ strtoupper(str_replace('_',' ', $req->status)) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            Belum ada pengajuan terbaru.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
