@section('title', 'Tambah Template Surat')

<x-app-layout>
    <x-slot name="header">
        <h2 class="h5 mb-0 d-flex align-items-center gap-2">
            <i class="bi bi-plus-circle"></i>
            Tambah Template Surat
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="container">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.letter_templates.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Jenis Surat</label>
                            <select name="letter_type" class="form-select" required>
                                <option value="">-- Pilih --</option>
                                <option value="bebas_pustaka" @selected(old('letter_type')==='bebas_pustaka')>Bebas Pustaka</option>
                                <option value="penyerahan_skripsi" @selected(old('letter_type')==='penyerahan_skripsi')>Penyerahan Skripsi</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Judul (opsional)</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Isi Template</label>
                            <textarea name="body" class="form-control" rows="5" placeholder="Gunakan placeholder: {nama}, {nim}, {prodi}, {judul_skripsi}, {tahun_lulus}">{{ old('body') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Footer (opsional)</label>
                            <textarea name="footer" class="form-control" rows="3">{{ old('footer') }}</textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.letter_templates.index') }}" class="btn btn-outline-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm border-0 mt-4">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">Preview Template</h6>
                    <div class="border rounded p-3 bg-light">
                        <div class="fw-semibold" id="preview-title">JUDUL SURAT</div>
                        <hr class="my-2">
                        <div id="preview-body" class="mb-3 text-muted">
                            Isi surat akan tampil di sini.
                        </div>
                        <div id="preview-footer" class="text-muted small">
                            Footer akan tampil di sini.
                        </div>
                    </div>
                    <div class="text-muted small mt-2">
                        Data preview: {nama}=Andi Pratama, {nim}=20210001, {prodi}=Teknik Informatika, {judul_skripsi}=Sistem Informasi Akademik, {tahun_lulus}=2026
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@push('scripts')
    <script>
        (function () {
            const sample = {
                nama: 'Andi Pratama',
                nim: '20210001',
                prodi: 'Teknik Informatika',
                judul_skripsi: 'Sistem Informasi Akademik',
                tahun_lulus: '2026'
            };

            function render(text) {
                let output = text || '';
                Object.keys(sample).forEach((key) => {
                    output = output.replaceAll('{' + key + '}', sample[key]);
                });
                return output || '-';
            }

            const titleEl = document.querySelector('input[name="title"]');
            const bodyEl = document.querySelector('textarea[name="body"]');
            const footerEl = document.querySelector('textarea[name="footer"]');

            const previewTitle = document.getElementById('preview-title');
            const previewBody = document.getElementById('preview-body');
            const previewFooter = document.getElementById('preview-footer');

            function updatePreview() {
                previewTitle.textContent = render(titleEl?.value || 'JUDUL SURAT');
                previewBody.textContent = render(bodyEl?.value || 'Isi surat akan tampil di sini.');
                previewFooter.textContent = render(footerEl?.value || 'Footer akan tampil di sini.');
            }

            [titleEl, bodyEl, footerEl].forEach((el) => {
                if (el) {
                    el.addEventListener('input', updatePreview);
                }
            });

            updatePreview();
        })();
    </script>
@endpush
