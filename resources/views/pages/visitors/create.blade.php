<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Pengunjung Perpustakaan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('pages.partials.public-head')
</head>
<body data-bs-theme="light">
@include('pages.partials.public-nav')

<main class="py-5">
    <div class="container" style="max-width: 720px;">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4 p-md-5">
                <h3 class="fw-bold mb-2">Form Pengunjung</h3>
                <p class="text-muted mb-4">Isi data kunjungan Anda dengan benar.</p>

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('visitors.store') }}">
                    @csrf

                    {{-- ================= JENIS PENGUNJUNG ================= --}}
                    <div class="mb-3">
                        <label class="form-label d-block">
                            Jenis Pengunjung
                        </label>

                        <div class="d-flex gap-4">
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="visitor_type"
                                    id="visitor-mahasiswa"
                                    value="mahasiswa"
                                    @checked(old('visitor_type', 'mahasiswa') === 'mahasiswa')
                                >
                                <label class="form-check-label" for="visitor-mahasiswa">
                                    Mahasiswa
                                </label>
                            </div>

                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="visitor_type"
                                    id="visitor-umum"
                                    value="umum"
                                    @checked(old('visitor_type') === 'umum')
                                >
                                <label class="form-check-label" for="visitor-umum">
                                    Umum
                                </label>
                            </div>
                        </div>

                        @error('visitor_type')
                            <div class="text-danger small mt-2">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- ================= NAMA ================= --}}
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- ================= FORM MAHASISWA ================= --}}
                    <div id="form-mahasiswa">
                        <div class="mb-3">
                            <label class="form-label">NIM</label>
                            <input type="text" name="nim"
                                   class="form-control @error('nim') is-invalid @enderror"
                                   value="{{ old('nim') }}">
                            @error('nim')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Program Studi</label>
                            <select name="department_id"
                                    class="form-select @error('department_id') is-invalid @enderror">
                                <option value="">-- Pilih Program Studi --</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}"
                                        @selected(old('department_id') == $department->id)>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- ================= FORM UMUM ================= --}}
                    <div id="form-umum" class="d-none">
                        <div class="mb-3">
                            <label class="form-label">Instansi</label>
                            <input type="text" name="institution"
                                   class="form-control @error('institution') is-invalid @enderror"
                                   value="{{ old('institution') }}">
                            @error('institution')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- ================= KEPERLUAN ================= --}}
                    <div class="mb-4">
                        <label class="form-label d-block">Keperluan</label>

                        @php
                            $purposes = [
                                'Membaca di tempat',
                                'Meminjam buku',
                                'Mengembalikan buku',
                                'Lainnya',
                            ];
                            $oldPurposes = collect(old('purpose', []));
                        @endphp

                        <div class="d-flex flex-column gap-2">
                            @foreach ($purposes as $item)
                                <div class="form-check">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           name="purpose[]"
                                           value="{{ $item }}"
                                           @checked($oldPurposes->contains($item))>
                                    <label class="form-check-label">
                                        {{ $item }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Simpan Kunjungan
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>

@include('pages.partials.public-scripts')

<script>
    function toggleVisitorForm() {
        const type = document.querySelector('input[name="visitor_type"]:checked')?.value;

        document.getElementById('form-mahasiswa')
            .classList.toggle('d-none', type !== 'mahasiswa');

        document.getElementById('form-umum')
            .classList.toggle('d-none', type !== 'umum');
    }

    document
        .querySelectorAll('input[name="visitor_type"]')
        .forEach(el => el.addEventListener('change', toggleVisitorForm));

    toggleVisitorForm(); // init saat load
</script>

</body>
</html>
