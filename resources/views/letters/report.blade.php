@section('title', 'Laporan Surat')

<x-app-layout>
    <x-slot name="header">
        <h2 class="h5 mb-0 d-flex align-items-center gap-2">
            <i class="bi bi-bar-chart-line"></i>
            Laporan Surat
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">

                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">

                            <form action="{{ url('/laporan/cetak') }}"
                                  method="POST"
                                  target="_blank">
                                @csrf

                                {{-- Bulan --}}
                                <div class="mb-3">
                                    <label class="form-label">
                                        Bulan
                                    </label>
                                    <select name="bulan"
                                            class="form-select"
                                            required>
                                        <option value="">-- Pilih Bulan --</option>
                                        @for($i=1;$i<=12;$i++)
                                            <option value="{{ $i }}">
                                                {{ DateTime::createFromFormat('!m',$i)->format('F') }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>

                                {{-- Tahun --}}
                                <div class="mb-4">
                                    <label class="form-label">
                                        Tahun
                                    </label>
                                    <input type="number"
                                           name="tahun"
                                           value="{{ date('Y') }}"
                                           class="form-control"
                                           required>
                                </div>

                                {{-- Action --}}
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="submit"
                                            class="btn btn-primary">
                                        <i class="bi bi-printer"></i>
                                        Cetak Laporan
                                    </button>
                                    <button type="submit"
                                            formaction="{{ route('letters.report.export') }}"
                                            class="btn btn-outline-secondary">
                                        <i class="bi bi-download"></i>
                                        Export CSV
                                    </button>
                                </div>

                            </form>

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
