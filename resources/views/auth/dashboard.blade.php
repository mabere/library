<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                <div class="bg-white p-4 rounded shadow">
                    <h4>Total Surat</h4>
                    <p class="text-2xl font-bold">{{ $totalSurat }}</p>
                </div>

                <div class="bg-white p-4 rounded shadow">
                    <h4>Bebas Pustaka</h4>
                    <p class="text-2xl font-bold">{{ $totalBebas }}</p>
                </div>

                <div class="bg-white p-4 rounded shadow">
                    <h4>Penyerahan Skripsi</h4>
                    <p class="text-2xl font-bold">{{ $totalSkripsi }}</p>
                </div>

                <div class="bg-white p-4 rounded shadow">
                    <h4>Hari Ini</h4>
                    <p class="text-2xl font-bold">{{ $hariIni }}</p>
                </div>

            </div>

        </div>
    </div>
@push('scripts')


    <script>
        // BAR CHART
        new Chart(document.getElementById('chartBulanan'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Jumlah Surat',
                    data: {!! json_encode($chartValues) !!},
                    backgroundColor: '#0d6efd'
                }]
            }
        });

        // PIE CHART
        new Chart(document.getElementById('chartPie'), {
            type: 'pie',
            data: {
                labels: ['Bebas Pustaka', 'Penyerahan Skripsi'],
                datasets: [{
                    data: [
                        {{ $pieData['bebas'] }},
                        {{ $pieData['skripsi'] }}
                    ],
                    backgroundColor: ['#198754', '#0dcaf0']
                }]
            }
        });
    </script>
    @endpush
</x-app-layout>
