@section('title', 'Data Mahasiswa')

<x-app-layout>
    <x-slot name="header">
        <h2 class="h5 mb-0">
            Data Mahasiswa
        </h2>
    </x-slot>

    <div class="container py-4">
        {{-- <x-notification-panel /> --}}

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>NIM</th>
                                <th>Nama</th>
                                <th>Prodi</th>
                                <th>Akun</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($students as $student)
                                <tr>
                                    <td>{{ $student->nim }}</td>
                                    <td>{{ $student->nama }}</td>
                                    <td>{{ $student->department?->name ?? '-' }}</td>
                                    <td>
                                        @if($student->user)
                                            <span class="badge text-bg-success">
                                                Terhubung
                                            </span>
                                        @else
                                            <span class="badge text-bg-secondary">
                                                Belum
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @can('update', $student)
                                            <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-sm btn-outline-secondary">Koreksi</a>
                                        @endcan

                                        @can('assignUser', $student)
                                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#assignUserModal-{{ $student->id }}">Buat Akun</button>
                                        @endcan
                                    </td>
                                </tr>

                                Modal Assign User
                                @can('assignUser', $student)
                                    @include('students.assign-user-modal', [
                                        'student' => $student
                                    ])
                                @endcan

                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Tidak ada data mahasiswa.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-3">
            {{ $students->links() }}
        </div>
    </div>
</x-app-layout>
