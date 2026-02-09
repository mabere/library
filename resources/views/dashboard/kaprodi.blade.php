@section('title', 'Dashboard Kaprodi')

<x-app-layout>
    <x-slot name="header">
        <h2 class="h5 mb-0 d-flex align-items-center gap-2">
            <i class="bi bi-diagram-3"></i>
            Dashboard Kaprodi
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="container">
            {{-- <x-notification-panel /> --}}

            {{-- ================= RINGKASAN PENGAJUAN ================= --}}
            <h6 class="text-uppercase text-muted mb-2">
                Ringkasan Pengajuan
            </h6>

            <div class="row g-3 mb-4 text-center">
                <div class="col-6 col-md">
                    <x-dashboard.stat title="Total" :value="$totalPengajuan" />
                </div>
                <div class="col-6 col-md">
                    <x-dashboard.stat title="Menunggu" :value="$menunggu" />
                </div>
                <div class="col-6 col-md">
                    <x-dashboard.stat title="Diverifikasi" :value="$diverifikasi" />
                </div>
                <div class="col-6 col-md">
                    <x-dashboard.stat title="Ditolak" :value="$ditolak" />
                </div>
                <div class="col-6 col-md">
                    <x-dashboard.stat title="Disetujui" :value="$disetujui" />
                </div>
            </div>

            {{-- ================= SURAT TERBIT ================= --}}
            <h6 class="text-uppercase text-muted mb-2">
                Surat Terbit
            </h6>

            <div class="row g-3 mb-4 text-center">
                <div class="col-md-4">
                    <x-dashboard.stat title="Total Surat" :value="$totalSurat" />
                </div>
                <div class="col-md-4">
                    <x-dashboard.stat title="Bebas Pustaka" :value="$totalBebas" />
                </div>
                <div class="col-md-4">
                    <x-dashboard.stat title="Penyerahan Skripsi" :value="$totalSkripsi" />
                </div>
            </div>

            {{-- ================= ACTION ================= --}}
            <div class="d-flex gap-2 justify-content-end mb-4">
                <a href="{{ route('kaprodi.bebas_pustaka.index') }}" class="btn btn-dark">
                    <i class="bi bi-eye"></i>
                    Bebas Pustaka
                </a>
                <a href="{{ route('kaprodi.skripsi.index') }}" class="btn btn-outline-dark">
                    <i class="bi bi-eye"></i>
                    Skripsi
                </a>
            </div>

            {{-- ================= PENGAJUAN TERBARU ================= --}}
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
                                    <th>Tipe Surat</th>
                                    <th>NIM</th>
                                    <th>Nama</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recent as $req)
                                    <tr>
                                        <td>
                                            {{ optional($req->submitted_at)->format('d-m-Y H:i') }}
                                        </td>
                                        <td>
                                            <span class="badge text-bg-light">
                                                {{ $req->type === 'bebas_pustaka'
                                                    ? 'Bebas Pustaka'
                                                    : 'Penyerahan Skripsi' }}
                                            </span>
                                        </td>
                                        <td>{{ $req->nim }}</td>
                                        <td>{{ $req->nama }}</td>
                                        <td>
                                            <x-status-badge :status="$req->status" />
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5"
                                            class="text-center text-muted py-4">
                                            Belum ada data.
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
