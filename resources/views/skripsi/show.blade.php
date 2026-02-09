@section('title', 'Detail Pengajuan Skripsi')

<x-app-layout>
    <x-slot name="header">
        <h2 class="h5 mb-0 d-flex align-items-center gap-2">
            <i class="bi bi-file-text"></i>
            Detail Pengajuan Penyerahan Skripsi
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="container">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="text-muted small">NIM</div>
                            <div class="fw-semibold">{{ $req->nim }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Nama</div>
                            <div class="fw-semibold">{{ $req->nama }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Program Studi</div>
                            <div class="fw-semibold">{{ $req->student?->department?->name ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Judul Skripsi</div>
                            <div class="fw-semibold">{{ $req->judul_skripsi }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Tahun Lulus</div>
                            <div class="fw-semibold">{{ $req->tahun_lulus }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Status</div>
                            <div class="fw-semibold">
                                @php($status = $req->statusEnum())
                                <span class="badge text-bg-{{ $status->badgeClass() }}">
                                    {{ strtoupper(str_replace('_',' ', $status->label()))  }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Diajukan</div>
                            <div class="fw-semibold">{{ optional($req->submitted_at)->format('d-m-Y H:i') ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Diverifikasi</div>
                            <div class="fw-semibold">{{ optional($req->verified_at)->format('d-m-Y H:i') ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Disetujui</div>
                            <div class="fw-semibold">{{ optional($req->approved_at)->format('d-m-Y H:i') ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Petugas Verifikasi</div>
                            <div class="fw-semibold">{{ $req->verifiedBy?->name ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Petugas Persetujuan</div>
                            <div class="fw-semibold">{{ $req->approvedBy?->name ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Catatan</div>
                            <div class="fw-semibold">{{ $req->rejection_note ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Surat</div>
                            <div class="fw-semibold">
                                @if($req->letter_id)
                                    <a href="{{ route('skripsi.download', $req->id) }}" class="btn btn-sm btn-outline-primary">
                                        Unduh Surat
                                    </a>
                                @else
                                    <span class="text-muted">Belum ada</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Riwayat Status</h5>
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
                        <div class="text-muted">Belum ada riwayat.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
