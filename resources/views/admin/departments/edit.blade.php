<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">Edit Department</h2>
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
            <form method="POST" action="{{ route('kepala.departments.update', $department->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $department->name) }}" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Kode (opsional)</label>
                    <input type="text" name="code" class="form-control" value="{{ old('code', $department->code) }}">
                </div>

                <div class="mb-4">
                    <label class="form-label">Kaprodi (opsional)</label>
                    <select name="kaprodi_user_id" class="form-select">
                        <option value="">-- Pilih Kaprodi --</option>
                        @foreach ($kaprodiUsers as $user)
                            <option value="{{ $user->id }}" @selected(old('kaprodi_user_id', $selectedKaprodiUserId) == $user->id)>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                </div>



                <div class="d-flex gap-2">
                    <a href="{{ route('kepala.departments.index') }}" class="btn btn-outline-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>




id  department_id   user_id role_in_department
1   4               1       kaprodi
