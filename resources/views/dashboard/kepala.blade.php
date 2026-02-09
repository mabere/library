@section('title', 'Dashboard Kepala Perpustakaan')

<x-app-layout>
    <x-slot name="header">
        <h2 class="h5 mb-0 d-flex align-items-center gap-2">
            <i class="bi bi-building-check"></i>
            Dashboard Kepala Perpustakaan
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="container">
            <x-notification-panel />

            {{-- ================= STATISTIK UTAMA ================= --}}
            <div class="row g-3 mb-4 text-center">
                <div class="col-md-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">
                            <div class="text-muted small">Menunggu Persetujuan</div>
                            <div class="fs-3 fw-bold text-warning">
                                {{ $menungguPersetujuan }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">
                            <div class="text-muted small">Total Disetujui</div>
                            <div class="fs-3 fw-bold text-success">
                                {{ $disetujuiTotal }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ================= ACTION ================= --}}
            <div class="d-flex justify-content-end mb-4">
                <a href="{{ route('kepala.bebas_pustaka.index') }}"
                   class="btn btn-primary">
                    <i class="bi bi-check2-square"></i>
                    Tinjau Persetujuan
                </a>
            </div>

            {{-- ================= PENDING APPROVAL TABLE ================= --}}
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">

                    <h5 class="fw-semibold mb-3">
                        Menunggu Persetujuan
                    </h5>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal Verifikasi</th>
                                    <th>NIM</th>
                                    <th>Nama</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recent as $req)
                                    <tr>
                                        <td>
                                            {{ optional($req->verified_at)->format('d-m-Y H:i') ?? '-' }}
                                        </td>
                                        <td>{{ $req->nim }}</td>
                                        <td>{{ $req->nama }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">
                                            Tidak ada pengajuan menunggu persetujuan.
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
