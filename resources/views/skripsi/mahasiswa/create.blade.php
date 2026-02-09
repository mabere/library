<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">Ajukan Penyerahan Skripsi</h2>
    </x-slot>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('mahasiswa.skripsi.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">NIM</label>
                    <input class="form-control" value="{{ $student->nim }}" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Mahasiswa</label>
                    <input class="form-control" value="{{ $student->nama }}" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label">Program Studi</label>
                    <input class="form-control" value="{{ $student->department?->name ?? '-' }}" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label">Judul Skripsi</label>
                    <textarea name="judul_skripsi" class="form-control" required>{{ old('judul_skripsi') }}</textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label">Tahun Lulus</label>
                    <input type="number" name="tahun_lulus" class="form-control" value="{{ old('tahun_lulus') }}" required>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('mahasiswa.skripsi.index') }}" class="btn btn-outline-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
