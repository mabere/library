<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">Mapping User ↔ Mahasiswa</h2>
    </x-slot>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

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
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Nama User</th>
                            <th>Email</th>
                            <th>Mahasiswa Terhubung</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <form method="POST" action="{{ route('staf.user_student.update', $user->id) }}">
                                        @csrf
                                        <select name="student_id" class="form-select">
                                            <option value="">-- Belum Di-mapping --</option>
                                            @foreach ($students as $student)
                                                <option value="{{ $student->id }}"
                                                    @selected($user->student_id === $student->id)>
                                                    {{ $student->nim }} - {{ $student->nama }} ({{ $student->department?->name ?? '-' }})
                                                </option>
                                            @endforeach
                                        </select>
                                </td>
                                <td class="text-end">
                                        <button type="submit" class="btn btn-primary">
                                            Simpan
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-muted">Belum ada user mahasiswa.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
