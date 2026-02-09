<x-app-layout>
    <x-slot name="header">
        <h2 class="h5 mb-0 d-flex align-items-center gap-2">
            <i class="bi bi-speedometer2"></i>
            Dashboard Mahasiswa
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="container">
            <x-notification-panel />

            {{-- ================= PROGRESS CARD ================= --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">

                    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                        <h5 class="fw-semibold mb-2 mb-md-0">
                            Progress Pengajuan Bebas Pustaka
                        </h5>

                        @php
                            $canSubmit = !$sedangDiproses && !$sudahDisetujui;
                        @endphp

                        @if($canSubmit)
                            <a href="{{ route('mahasiswa.bebas_pustaka.create') }}"
                               class="btn btn-dark">
                                <i class="bi bi-plus-circle"></i>
                                Ajukan Bebas Pustaka
                            </a>
                        @else
                            <button class="btn btn-secondary" disabled>
                                <i class="bi bi-lock"></i>
                                Ajukan Bebas Pustaka
                            </button>
                        @endif
                    </div>

                    {{-- Statistik --}}
                    <div class="row g-3 text-center">
                        <div class="col-6 col-md-3">
                            <div class="border rounded p-3 h-100">
                                <div class="text-muted small">Total Pengajuan</div>
                                <div class="fs-4 fw-bold">{{ $totalPengajuan }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="border rounded p-3 h-100">
                                <div class="text-muted small">Menunggu</div>
                                <div class="fs-4 fw-bold">{{ $menunggu }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="border rounded p-3 h-100">
                                <div class="text-muted small">Ditolak</div>
                                <div class="fs-4 fw-bold">{{ $ditolak }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="border rounded p-3 h-100">
                                <div class="text-muted small">Disetujui</div>
                                <div class="fs-4 fw-bold">{{ $disetujui }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Informasi Status --}}
                    <div class="mt-4 small text-muted">
                        Alur: <strong>Diajukan</strong> → <strong>Diverifikasi Staf</strong> →
                        <strong>Disetujui Kepala</strong> / <strong>Ditolak</strong>
                    </div>

                    @if($sedangDiproses)
                        <div class="alert alert-warning mt-3 mb-0">
                            <i class="bi bi-hourglass-split"></i>
                            Pengajuan sedang diproses. Anda tidak dapat mengajukan kembali.
                        </div>
                    @elseif($sudahDisetujui)
                        <div class="alert alert-success mt-3 mb-0">
                            <i class="bi bi-check-circle"></i>
                            Pengajuan telah disetujui. Bebas pustaka tidak dapat diajukan ulang.
                        </div>
                    @endif

                </div>
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
                                    <th>Status</th>
                                    <th>Progress</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recent as $req)
                                    @php
                                        $steps = [
                                            'diajukan' => 1,
                                            'diverifikasi_staf' => 2,
                                            'disetujui_kepala' => 3,
                                            'ditolak_staf' => 3,
                                        ];
                                        $current = $steps[$req->status] ?? 1;
                                        $isRejected = $req->status === 'ditolak_staf';
                                    @endphp
                                    <tr>
                                        <td>
                                            {{ optional($req->submitted_at)->format('d-m-Y H:i') ?? '-' }}
                                        </td>
                                        <td>{{ $req->nim }}</td>
                                        <td>
                                            <span class="badge
                                                @if($req->status === 'disetujui_kepala') text-bg-success
                                                @elseif($req->status === 'ditolak_staf') text-bg-danger
                                                @elseif($req->status === 'diverifikasi_staf') text-bg-warning
                                                @else text-bg-secondary
                                                @endif">
                                                {{ strtoupper(str_replace('_',' ', $req->status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="d-flex gap-1">
                                                    <span class="rounded-circle {{ $current >= 1 ? 'bg-dark' : 'bg-secondary' }}" style="width:8px;height:8px;"></span>
                                                    <span class="rounded-circle {{ $current >= 2 ? 'bg-dark' : 'bg-secondary' }}" style="width:8px;height:8px;"></span>
                                                    <span class="rounded-circle
                                                        {{ $current >= 3 ? ($isRejected ? 'bg-danger' : 'bg-success') : 'bg-secondary' }}"
                                                        style="width:8px;height:8px;">
                                                    </span>
                                                </div>
                                                <small class="{{ $isRejected ? 'text-danger' : 'text-muted' }}">
                                                    {{ ucfirst(str_replace('_',' ', $req->status)) }}
                                                </small>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @if($req->letter_id)
                                                <a href="{{ route('bebas_pustaka.download', $req->id) }}"
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-download"></i>
                                                    Unduh
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            Belum ada pengajuan.
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
