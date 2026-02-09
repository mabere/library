@section('title', 'Pengajuan Penyerahan Skripsi')

<x-app-layout>
    <x-slot name="header">
        <h2 class="h5 mb-0 d-flex align-items-center gap-2">
            <i class="bi bi-journal-text"></i>
            Pengajuan Penyerahan Skripsi
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="container">

            {{-- ================= FLASH MESSAGE ================= --}}
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="bi bi-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-circle"></i>
                    {{ session('error') }}
                </div>
            @endif

            {{-- ================= ACTION ================= --}}
            <div class="d-flex justify-content-end mb-3">
                @if($canSubmit)
                    <a href="{{ route('mahasiswa.skripsi.create') }}"
                       class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i>
                        Ajukan Baru
                    </a>
                @else
                    <button class="btn btn-secondary" disabled>
                        <i class="bi bi-lock"></i>
                        Ajukan Baru
                    </button>
                @endif
            </div>

            {{-- ================= STATUS INFO ================= --}}
            @if(!$canSubmit && $statusInfo)
                <div class="alert alert-warning">
                    <i class="bi bi-info-circle"></i>
                    {{ $statusInfo }}
                </div>
            @endif

            {{-- ================= TABLE ================= --}}
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>NIM</th>
                                    <th>Judul Skripsi</th>
                                    <th>Tahun Lulus</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($requests as $req)
                                    <tr>
                                        <td>
                                            {{ optional($req->submitted_at)->format('d-m-Y H:i') ?? '-' }}
                                        </td>
                                        <td>{{ $req->nim }}</td>
                                        <td>
                                            {{ $req->judul_skripsi ?? '-' }}
                                        </td>
                                        <td>
                                            {{ $req->tahun_lulus ?? '-' }}
                                        </td>
                                        <td>
                                            @php($status = $req->statusEnum())

                                            <span class="badge text-bg-{{ $status->badgeClass() }}">
                                                {{ strtoupper(str_replace('_',' ', $status->label()))  }}
                                            </span>

                                        </td>
                                        <td class="text-center">
                                            @if($req->letter_id)
                                                <a href="{{ route('skripsi.download', $req->id) }}"
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-download"></i>
                                                    Unduh Surat
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif

                                            <a href="{{ route('skripsi.show', $req->id) }}"
                                               class="btn btn-sm btn-outline-secondary mt-2">
                                                Detail
                                            </a>

                                            <div class="mt-2">
                                                <button class="btn btn-sm btn-outline-secondary"
                                                    type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#history-skripsi-{{ $req->id }}"
                                                    aria-expanded="false"
                                                    aria-controls="history-skripsi-{{ $req->id }}">
                                                    Riwayat
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- ================= HISTORY ================= --}}
                                    <tr class="collapse" id="history-skripsi-{{ $req->id }}">
                                        <td colspan="5" class="bg-light">
                                            @if($req->histories->count())
                                                <ul class="list-group list-group-flush">
                                                    @foreach($req->histories as $item)
                                                        <li class="list-group-item">
                                                            <div class="fw-semibold">
                                                                {{ strtoupper(str_replace('_',' ', $item->to_status)) }}
                                                            </div>
                                                            <small class="text-muted">
                                                                {{ optional($item->created_at)->format('d-m-Y H:i') ?? '-' }}
                                                                @if($item->user)
                                                                    · {{ $item->user->name }}
                                                                @endif
                                                            </small>

                                                            @if($item->note)
                                                                <div class="text-muted small mt-1">
                                                                    {{ $item->note }}
                                                                </div>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <span class="text-muted">Belum ada riwayat.</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5"
                                            class="text-center text-muted py-4">
                                            Belum ada pengajuan penyerahan skripsi.
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
