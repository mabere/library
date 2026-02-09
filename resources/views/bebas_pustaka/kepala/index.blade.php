@section('title', 'Persetujuan Bebas Pustaka')

<x-app-layout>
    <x-slot name="header">
        <h2 class="h5 mb-0 d-flex align-items-center gap-2">
            <i class="bi bi-check2-square"></i>
            Persetujuan Bebas Pustaka
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
                                    <th>Tanggal Verifikasi</th>
                                    <th>NIM</th>
                                    <th>Nama</th>
                                    <th>Diverifikasi Oleh</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($requests as $req)
                                    <tr>
                                        <td>
                                            {{ optional($req->verified_at)->format('d-m-Y H:i') ?? '-' }}
                                        </td>
                                        <td>{{ $req->nim }}</td>
                                        <td>{{ $req->nama }}</td>
                                        <td>{{ $req->verifiedBy?->name ?? '-' }}</td>
                                        <td class="text-end">
                                            <form method="POST"
                                                  action="{{ route('kepala.bebas_pustaka.approve', $req->id) }}"
                                                  onsubmit="return confirm('Setujui pengajuan dan buat surat?')">
                                                @csrf
                                                @method('PUT')

                                                <button type="submit"
                                                        class="btn btn-sm btn-primary">
                                                    <i class="bi bi-file-earmark-check"></i>
                                                    Setujui & Buat Surat
                                                </button>
                                            </form>
                                            <a href="{{ route('bebas_pustaka.show', $req->id) }}"
                                               class="btn btn-sm btn-outline-secondary mt-2">
                                                Detail
                                            </a>
                                            <button class="btn btn-sm btn-outline-secondary mt-2"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#history-bp-{{ $req->id }}"
                                                aria-expanded="false"
                                                aria-controls="history-bp-{{ $req->id }}">
                                                Riwayat
                                            </button>
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
                                            Belum ada pengajuan yang menunggu persetujuan.
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
