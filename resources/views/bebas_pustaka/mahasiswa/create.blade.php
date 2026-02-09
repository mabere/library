@section('title', 'Ajukan Bebas Pustaka')

<x-app-layout>
    <x-slot name="header">
        <h2 class="h5 mb-0 d-flex align-items-center gap-2">
            <i class="bi bi-journal-plus"></i>
            Ajukan Bebas Pustaka
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">

                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">

                            {{-- ================= ERROR ================= --}}
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <i class="bi bi-exclamation-triangle"></i>
                                    <ul class="mb-0 mt-2 ps-3">
                                        @foreach ($errors->all() as $error)
                                            <li class="small">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST"
                                  action="{{ route('mahasiswa.bebas_pustaka.store') }}">
                                @csrf

                                {{-- NIM --}}
                                <div class="mb-3">
                                    <label class="form-label">
                                        NIM
                                    </label>
                                    <input type="text"
                                           class="form-control"
                                           value="{{ $student->nim }}"
                                           disabled>
                                </div>

                                {{-- Nama --}}
                                <div class="mb-3">
                                    <label class="form-label">
                                        Nama Mahasiswa
                                    </label>
                                    <input type="text"
                                           class="form-control"
                                           value="{{ $student->nama }}"
                                           disabled>
                                </div>

                                {{-- Prodi --}}
                                <div class="mb-4">
                                    <label class="form-label">
                                        Program Studi
                                    </label>
                                    <input type="text"
                                           class="form-control"
                                           value="{{ $student->department?->name ?? '-' }}"
                                           disabled>
                                </div>

                                <hr>

                                {{-- Action --}}
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('mahasiswa.bebas_pustaka.index') }}"
                                       class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-left"></i>
                                        Kembali
                                    </a>
                                    <button type="submit"
                                            class="btn btn-primary">
                                        <i class="bi bi-send"></i>
                                        Kirim Pengajuan
                                    </button>
                                </div>

                            </form>

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
