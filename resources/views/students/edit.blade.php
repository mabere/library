@section('title', 'Koreksi Data Mahasiswa')

<x-app-layout>
    <x-slot name="header">
        <h2 class="h5 mb-0">
            Koreksi Data Mahasiswa
        </h2>
    </x-slot>

    <div class="container py-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">

                <form method="POST"
                      action="{{ route('admin.students.update', $student) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">NIM</label>
                        <input type="text"
                               class="form-control"
                               value="{{ $student->nim }}"
                               disabled>
                        <small class="text-muted">
                            NIM tidak dapat diubah.
                        </small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Mahasiswa</label>
                        <input type="text"
                               name="nama"
                               class="form-control"
                               value="{{ old('nama', $student->nama) }}"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Program Studi</label>
                        <select name="department_id"
                                class="form-select"
                                required>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}"
                                    @selected($dept->id === $student->department_id)>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.students.index') }}"
                           class="btn btn-secondary">
                            Kembali
                        </a>
                        <button class="btn btn-primary">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
