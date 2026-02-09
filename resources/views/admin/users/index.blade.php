<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">Manajemen User</h2>
    </x-slot>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @unless($readOnly ?? false)
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                Tambah User
            </a>
        </div>
    @endunless

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    {{ $user->roles->pluck('name')->join(', ') ?: '-' }}
                                </td>
                                <td class="text-end">
                                    @if($readOnly ?? false)
                                        <span class="text-muted">-</span>
                                    @else
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-secondary">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Hapus user ini?')">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-muted">Belum ada user.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
