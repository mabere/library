@section('title', 'Verifikasi Bebas Pustaka')

<x-app-layout>
    <x-slot name="header">
        <h2 class="h5 mb-0 d-flex align-items-center gap-2">
            <i class="bi bi-clipboard-check"></i>
            Verifikasi Bebas Pustaka
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="container-fluid">

            {{-- ================= FLASH MESSAGE ================= --}}
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="bi bi-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i>
                    {{ session('error') }}
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
                                    <th>Nama</th>
                                    <th>Status</th>
                                    <th style="width:260px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($requests as $req)
                                    <tr>
                                        <td>
                                            {{ optional($req->submitted_at)->format('d-m-Y H:i') ?? '-' }}
                                        </td>
                                        <td>{{ $req->nim }}</td>
                                        <td>{{ $req->nama }}</td>
                                        <td>
                                            <span class="badge
                                                @if($req->status === 'diajukan') text-bg-secondary
                                                @elseif($req->status === 'diverifikasi_staf') text-bg-warning
                                                @elseif($req->status === 'ditolak_staf') text-bg-danger
                                                @elseif($req->status === 'disetujui_kepala') text-bg-success
                                                @endif">
                                                {{ strtoupper(str_replace('_',' ', $req->status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($req->status === 'diajukan')

                                                {{-- VALIDASI --}}
                                                <form method="POST"
                                                      action="{{ route('staf.bebas_pustaka.verify', $req->id) }}"
                                                      class="mb-2">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="has_fine" value="0">
                                                    <button type="submit"
                                                            class="btn btn-sm btn-success w-100"
                                                            onclick="return confirm('Validasi pengajuan ini?')">
                                                        <i class="bi bi-check-lg"></i>
                                                        Validasi
                                                    </button>
                                                </form>

                                                {{-- TOLAK --}}
                                                <form method="POST"
                                                      action="{{ route('staf.bebas_pustaka.reject', $req->id) }}">
                                                    @csrf
                                                    @method('PUT')

                                                    <div class="input-group input-group-sm">
                                                        <input type="text"
                                                               name="rejection_note"
                                                               class="form-control"
                                                               placeholder="Alasan penolakan"
                                                               required>
                                                        <button type="submit"
                                                                class="btn btn-danger"
                                                                onclick="return confirm('Tolak pengajuan ini?')">
                                                            <i class="bi bi-x-lg"></i>
                                                            Tolak
                                                        </button>
                                                    </div>
                                                </form>

                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                            <a href="{{ route('bebas_pustaka.show', $req->id) }}"
                                               class="btn btn-sm btn-outline-secondary mt-2">
                                                Detail
                                            </a>
                                            <div class="mt-2">
                                                <button class="btn btn-sm btn-outline-secondary"
                                                    type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#history-bp-{{ $req->id }}"
                                                    aria-expanded="false"
                                                    aria-controls="history-bp-{{ $req->id }}">
                                                    Riwayat
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="collapse" id="history-bp-{{ $req->id }}">
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
