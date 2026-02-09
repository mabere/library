<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">Persetujuan Penyerahan Skripsi (Kepala)</h2>
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
                            <th>Tanggal Verifikasi</th>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Judul</th>
                            <th>Diverifikasi Oleh</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($requests as $req)
                            <tr>
                                <td>{{ optional($req->verified_at)->format('d-m-Y H:i') }}</td>
                                <td>{{ $req->nim }}</td>
                                <td>{{ $req->nama }}</td>
                                <td>{{ $req->judul_skripsi }}</td>
                                <td>{{ $req->verifiedBy?->name ?? '-' }}</td>
                                <td class="text-end">
                                    <form method="POST" action="{{ route('kepala.skripsi.approve', $req->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            Setujui & Buat Surat
                                        </button>
                                    </form>
                                    <a href="{{ route('skripsi.show', $req->id) }}" class="btn btn-sm btn-outline-secondary mt-2">
                                        Detail
                                    </a>
                                    <button class="btn btn-sm btn-outline-secondary mt-2"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#history-skripsi-{{ $req->id }}"
                                        aria-expanded="false"
                                        aria-controls="history-skripsi-{{ $req->id }}">
                                        Riwayat
                                    </button>
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
                                <td colspan="6" class="text-muted">Belum ada pengajuan yang menunggu.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
