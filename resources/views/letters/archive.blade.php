@section('title', 'Arsip Surat')

<x-app-layout>
    <x-slot name="header">
        <h2 class="h5 mb-0 d-flex align-items-center gap-2">
            <i class="bi bi-archive"></i>
            Arsip Surat
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="container-fluid">

            {{-- ================= FILTER ================= --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <form method="GET" class="row g-2 align-items-end">

                        <div class="col-md-4">
                            <label class="form-label small text-muted">
                                Pencarian
                            </label>
                            <input type="text"
                                   name="search"
                                   class="form-control"
                                   placeholder="Nama / NIM / Nomor Surat"
                                   value="{{ request('search') }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label small text-muted">
                                Jenis Surat
                            </label>
                            <select name="type" class="form-select">
                                <option value="">Semua</option>
                                <option value="bebas_pustaka" @selected(request('type')=='bebas_pustaka')>
                                    Bebas Pustaka
                                </option>
                                <option value="penyerahan_skripsi" @selected(request('type')=='penyerahan_skripsi')>
                                    Penyerahan Skripsi
                                </option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label small text-muted">
                                Bulan
                            </label>
                            <select name="month" class="form-select">
                                <option value="">Semua</option>
                                @for($i=1;$i<=12;$i++)
                                    <option value="{{ $i }}" @selected(request('month')==$i)>
                                        {{ date('F', mktime(0,0,0,$i,1)) }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label small text-muted">
                                Tahun
                            </label>
                            <select name="year" class="form-select">
                                <option value="">Semua</option>
                                @for($y=date('Y');$y>=2023;$y--)
                                    <option value="{{ $y }}" @selected(request('year')==$y)>
                                        {{ $y }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label small text-muted">
                                Status
                            </label>
                            <select name="status" class="form-select">
                                <option value="">Semua</option>
                                <option value="aktif" @selected(request('status')=='aktif')>Aktif</option>
                                <option value="dibatalkan" @selected(request('status')=='dibatalkan')>Dibatalkan</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label small text-muted">
                                Prodi
                            </label>
                            <select name="department_id" class="form-select">
                                <option value="">Semua</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" @selected(request('department_id')==$department->id)>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label small text-muted">
                                Dari
                            </label>
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label small text-muted">
                                Sampai
                            </label>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>

                        <div class="col-md-2 d-flex gap-2">
                            <button class="btn btn-primary w-100">
                                <i class="bi bi-funnel"></i>
                                Filter
                            </button>
                            <a href="{{ url('/arsip-surat') }}"
                               class="btn btn-outline-secondary w-100">
                                Reset
                            </a>
                        </div>
                        <div class="col-md-2 d-flex gap-2">
                            <a href="{{ route('letters.archive.export', request()->query()) }}"
                               class="btn btn-outline-primary w-100">
                                <i class="bi bi-download"></i>
                                Export CSV
                            </a>
                        </div>

                    </form>
                </div>
            </div>

            {{-- ================= TABLE ================= --}}
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:60px;">No</th>
                                    <th>Nomor Surat</th>
                                    <th>Nama</th>
                                    <th>Program Studi</th>
                                    <th>Jenis</th>
                                    <th>Tanggal</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($letters as $i => $row)
                                    <tr>
                                        <td>
                                            {{ $letters->firstItem() + $i }}
                                        </td>
                                        <td>{{ $row->letter_number }}</td>
                                        <td>{{ $row->student->nama }}</td>
                                        <td>
                                            {{ $row->student?->department?->name ?? '-' }}
                                        </td>
                                        <td>
                                            <span class="badge text-bg-success">
                                                {{ strtoupper(str_replace('_',' ',$row->letter_type)) }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ $row->created_at->format('d-m-Y') }}
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ asset('storage/'.$row->file_path) }}"
                                               target="_blank"
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-file-earmark-pdf"></i>
                                                PDF
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            Data tidak ditemukan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

            {{-- ================= PAGINATION ================= --}}
            <div class="mt-4">
                {{ $letters->withQueryString()->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
