@section('title', 'Template Surat')

<x-app-layout>
    <x-slot name="header">
        <h2 class="h5 mb-0 d-flex align-items-center gap-2">
            <i class="bi bi-file-earmark-text"></i>
            Template Surat
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="d-flex justify-content-end mb-3">
                <a href="{{ route('admin.letter_templates.create') }}" class="btn btn-primary">
                    Tambah Template
                </a>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Jenis Surat</th>
                                    <th>Judul</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($templates as $template)
                                    <tr>
                                        <td>{{ strtoupper(str_replace('_',' ', $template->letter_type)) }}</td>
                                        <td>{{ $template->title ?? '-' }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.letter_templates.edit', $template->id) }}"
                                               class="btn btn-sm btn-outline-secondary">
                                                Edit
                                            </a>
                                            <form method="POST"
                                                  action="{{ route('admin.letter_templates.destroy', $template->id) }}"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Hapus template ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">
                                            Belum ada template.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
