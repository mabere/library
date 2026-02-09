<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            📄 Buat Surat
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto bg-white shadow rounded-lg p-6">

            {{-- ERROR --}}
            @if ($errors->any())
                <div class="mb-4 rounded bg-red-100 p-4 text-red-700 text-sm">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 rounded bg-red-100 p-4 text-red-700 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ url('/letter') }}">
                @csrf

                {{-- ================================
                    JENIS SURAT
                ================================= --}}
                <div class="mb-6">
                    <x-input-label value="Jenis Surat" />
                    <select name="letter_type" id="letter_type"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-base"
                        required>
                        <option value="">-- Pilih Jenis Surat --</option>
                        <option value="bebas_pustaka">Surat Bebas Pustaka</option>
                        <option value="penyerahan_skripsi">Surat Penyerahan Skripsi</option>
                    </select>
                </div>

                {{-- ================================
                    DATA MAHASISWA
                ================================= --}}
                <div id="student-section" class="hidden space-y-4">

                    <h3 class="font-semibold text-gray-700 border-b pb-2">
                        Data Mahasiswa
                    </h3>

                    <div>
                        <x-input-label value="NIM" />
                        <x-text-input name="nim" class="w-full" />
                    </div>

                    <div>
                        <x-input-label value="Nama Mahasiswa" />
                        <x-text-input name="nama" class="w-full" />
                    </div>

                    <div>
                        <x-input-label value="Program Studi" />
                        <select name="department_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-base">
                            <option value="">-- Pilih Program Studi --</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-input-label value="Petugas Perpustakaan" />
                        <x-text-input name="verified_by" class="w-full" />
                    </div>
                </div>

                {{-- ================================
                    DATA SKRIPSI
                ================================= --}}
                <div id="skripsi-section" class="hidden mt-6 space-y-4">

                    <h3 class="font-semibold text-gray-700 border-b pb-2">
                        Data Skripsi
                    </h3>

                    <div>
                        <x-input-label value="Judul Skripsi" />
                        <textarea name="judul_skripsi"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </div>

                    <div>
                        <x-input-label value="Tahun Lulus" />
                        <x-text-input name="tahun_lulus" type="number" class="w-full" />
                    </div>
                </div>

                {{-- ================================
                    BEBAS PUSTAKA
                ================================= --}}
                <div id="bebas-section" class="hidden mt-6 space-y-4">

                    <h3 class="font-semibold text-gray-700 border-b pb-2">
                        Verifikasi Bebas Pustaka
                    </h3>

                    <div>
                        <x-input-label value="Status Peminjaman / Denda" />
                        <select name="has_fine" id="has_fine"
                            class="w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">-- Pilih --</option>
                            <option value="0">Tidak Ada Denda</option>
                            <option value="1">Masih Ada Denda</option>
                        </select>
                    </div>

                    <div id="fine-warning"
                        class="hidden rounded bg-red-100 p-3 text-red-700 text-sm">
                        Mahasiswa masih memiliki tanggungan. Surat tidak dapat diterbitkan.
                    </div>
                </div>

                {{-- ================================
                    BUTTON
                ================================= --}}
                <div class="mt-8 flex justify-end gap-3">
                    <a href="{{ url('/') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">
                        Kembali
                    </a>

                    <x-primary-button id="btn-submit">
                        Simpan & Buat Surat
                    </x-primary-button>
                </div>

            </form>
        </div>
    </div>

    {{-- ================================
        SCRIPT
    ================================= --}}
    @push('scripts')
    <script>
        const typeSelect = document.getElementById('letter_type');
        const studentSection = document.getElementById('student-section');
        const skripsiSection = document.getElementById('skripsi-section');
        const bebasSection = document.getElementById('bebas-section');
        const fineSelect = document.getElementById('has_fine');
        const warningBox = document.getElementById('fine-warning');
        const submitBtn = document.getElementById('btn-submit');

        typeSelect.addEventListener('change', function () {
            studentSection.classList.remove('hidden');
            skripsiSection.classList.add('hidden');
            bebasSection.classList.add('hidden');

            if (this.value === 'penyerahan_skripsi') {
                skripsiSection.classList.remove('hidden');
            }

            if (this.value === 'bebas_pustaka') {
                bebasSection.classList.remove('hidden');
            }
        });

        fineSelect?.addEventListener('change', function () {
            if (this.value === '1') {
                warningBox.classList.remove('hidden');
                submitBtn.disabled = true;
            } else {
                warningBox.classList.add('hidden');
                submitBtn.disabled = false;
            }
        });
    </script>
    @endpush
</x-app-layout>
