@section('title', 'Daftar Surat')

<x-app-layout>
    <x-slot name="header">
        <h2 class="h5 mb-0 d-flex align-items-center gap-2">
            <i class="bi bi-file-earmark-text"></i>
            Daftar Surat
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="container-fluid">

            {{-- ================= ACTION BAR ================= --}}
            <div class="d-flex justify-content-end mb-3">
                <a href="{{ url('/letter/create') }}"
                   class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i>
                    Buat Surat
                </a>
            </div>

            {{-- ================= FLASH MESSAGE ================= --}}
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="bi bi-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            {{-- ================= TABLE ================= --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Arsip Surat</h5>
                    <small class="text-muted">
                        Daftar surat yang telah dibuat oleh petugas
                    </small>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:60px;">No</th>
                                    <th>NIM</th>
                                    <th>Nama</th>
                                    <th>Program Studi</th>
                                    <th>Jenis Surat</th>
                                    <th>Nomor Surat</th>
                                    <th>Tanggal</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($letters as $item)
                                    <tr>
                                        <td>
                                            {{ $loop->iteration + (method_exists($letters,'firstItem') ? $letters->firstItem()-1 : 0) }}
                                        </td>
                                        <td>{{ $item->student->nim ?? '-' }}</td>
                                        <td>{{ $item->student->nama ?? '-' }}</td>
                                        <td>{{ $item->student?->department?->name ?? '-' }}</td>
                                        <td>
                                            @if($item->letter_type === 'bebas_pustaka')
                                                <span class="badge text-bg-success">
                                                    Bebas Pustaka
                                                </span>
                                            @else
                                                <span class="badge text-bg-info">
                                                    Penyerahan Skripsi
                                                </span>
                                            @endif
                                        </td>
                                        <td>{{ $item->letter_number }}</td>
                                        <td>{{ $item->created_at->format('d-m-Y') }}</td>
                                        <td class="text-center">
                                            <div class="d-inline-flex gap-2">

                                                <a href="{{ url('/letter/'.$item->id.'/download') }}"
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-download"></i>
                                                    Download
                                                </a>

                                                @if($item->status === 'aktif')
                                                    <form action="{{ url('/arsip-surat/'.$item->id.'/batal') }}"
                                                          method="POST"
                                                          onsubmit="return confirm('Yakin ingin membatalkan surat ini?')">
                                                        @csrf
                                                        @method('PUT')

                                                        <button type="submit"
                                                                class="btn btn-sm btn-outline-danger">
                                                            <i class="bi bi-x-circle"></i>
                                                            Batalkan
                                                        </button>
                                                    </form>
                                                @endif

                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8"
                                            class="text-center text-muted py-4">
                                            Belum ada surat dibuat.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ================= PAGINATION ================= --}}
                @if(method_exists($letters, 'links'))
                    <div class="card-footer bg-white">
                        {{ $letters->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
