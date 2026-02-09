@section('title', 'Hasil Verifikasi Surat')

<x-app-layout>
    <x-slot name="header">
        <h2 class="h5 mb-0 d-flex align-items-center gap-2">
            <i class="bi bi-shield-check"></i>
            Verifikasi Surat
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">

                    {{-- ================= VALID ================= --}}
                    @if($valid)
                        <div class="card shadow-sm border-0">
                            <div class="card-body p-4">

                                {{-- Status --}}
                                <div class="d-flex align-items-center mb-4">
                                    <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center me-3"
                                         style="width:48px;height:48px;">
                                        <i class="bi bi-check-lg fs-4"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-semibold text-success mb-1">
                                            Surat Valid
                                        </h5>
                                        <small class="text-muted">
                                            Surat terdaftar di sistem perpustakaan
                                        </small>
                                    </div>
                                </div>

                                {{-- Detail --}}
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm align-middle mb-0">
                                        <tbody>
                                            <tr>
                                                <th class="bg-light w-40">Nomor Surat</th>
                                                <td>{{ $letter->letter_number }}</td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light">Nama</th>
                                                <td>{{ $letter->student->nama }}</td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light">Jenis Surat</th>
                                                <td>{{ strtoupper(str_replace('_',' ', $letter->letter_type)) }}</td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light">Petugas</th>
                                                <td>{{ $letter->verified_by }}</td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light">Tanggal</th>
                                                <td>{{ $letter->created_at->format('d F Y') }}</td>
                                            </tr>

                                            @if($letter->letter_type === 'penyerahan_skripsi')
                                                <tr>
                                                    <th class="bg-light">Judul Skripsi</th>
                                                    <td>{{ $letter->student->judul_skripsi }}</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>

                                {{-- Action --}}
                                <div class="text-center mt-4">
                                    <a href="{{ route('dashboard') }}"
                                       class="btn btn-primary px-4">
                                        <i class="bi bi-arrow-left"></i>
                                        Kembali ke Dashboard
                                    </a>
                                </div>

                            </div>
                        </div>

                    {{-- ================= INVALID ================= --}}
                    @else
                        <div class="card shadow-sm border-0">
                            <div class="card-body p-4 text-center">

                                <div class="text-danger mb-3">
                                    <i class="bi bi-x-circle fs-1"></i>
                                </div>

                                <h5 class="fw-semibold text-danger">
                                    Surat Tidak Valid
                                </h5>

                                <p class="text-muted mb-4">
                                    {{ $error ?? 'QR Code tidak ditemukan atau surat sudah tidak berlaku.' }}
                                </p>

                                <a href="{{ url('/verify') }}"
                                   class="btn btn-secondary px-4">
                                    <i class="bi bi-arrow-repeat"></i>
                                    Verifikasi Ulang
                                </a>

                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
