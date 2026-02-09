<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">Verifikasi Penyerahan Skripsi (Staf)</h2>
    </x-slot>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Judul</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($requests as $req)
                            <tr>
                                <td>{{ optional($req->submitted_at)->format('d-m-Y H:i') }}</td>
                                <td>{{ $req->nim }}</td>
                                <td>{{ $req->nama }}</td>
                                <td>{{ $req->judul_skripsi }}</td>
                                <td>
                                    @php($status = $req->statusEnum())
                                    <span class="badge text-bg-{{ $status->badgeClass() }}">
                                        {{ strtoupper(str_replace('_',' ', $status->label()))  }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    @if($req->statusEnum() === \App\Enums\SkripsiStatus::DIAJUKAN)
                                        <form method="POST" action="{{ route('staf.skripsi.verify', $req->id) }}" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-success">
                                                Validasi
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('staf.skripsi.reject', $req->id) }}" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="text" name="rejection_note" class="form-control form-control-sm d-inline-block"
                                                style="width: 180px" placeholder="Alasan tolak">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                Tolak
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                    <a href="{{ route('skripsi.show', $req->id) }}" class="btn btn-sm btn-outline-secondary mt-2">
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
                            <tr class="collapse" id="history-skripsi-{{ $req->id }}">
                                <td colspan="6" class="bg-light">
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
                                <td colspan="6" class="text-muted">Belum ada pengajuan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
