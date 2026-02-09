<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">Manajemen Department</h2>
    </x-slot>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('kepala.departments.create') }}" class="btn btn-primary">
            Tambah Department
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Kode</th>
                            <th>Kaprodi</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($departments as $department)
                            <tr>
                                <td>{{ $department->name }}</td>
                                <td>{{ $department->code ?? '-' }}</td>
                                <td>
                                    {{ $department->users->first()?->name ?? '-' }}
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('kepala.departments.edit', $department->id) }}" class="btn btn-sm btn-outline-secondary">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('kepala.departments.destroy', $department->id) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Hapus department ini?')">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-muted">Belum ada department.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
