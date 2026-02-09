<?php

namespace App\Services\Dashboard;

use App\Models\Letter;
use App\Models\User;
use App\Models\Visitor;
use Carbon\Carbon;

class AdminDashboardService
{
    public function summary(): array
    {
        // === KARTU STATISTIK ===
        $totalSurat   = Letter::count();
        $totalBebas   = Letter::where('letter_type', 'bebas_pustaka')->count();
        $totalSkripsi = Letter::where('letter_type', 'penyerahan_skripsi')->count();
        $hariIni      = Letter::whereDate('created_at', today())->count();

        // === CHART BULANAN (6 BULAN TERAKHIR) ===
        $months = collect(range(5, 0))->map(function ($offset) {
            return Carbon::now()->subMonths($offset)->startOfMonth();
        });

        $chartLabels = $months
            ->map(fn ($date) => $date->translatedFormat('M Y'))
            ->values()
            ->all();

        $chartValues = $months
            ->map(function ($date) {
                return Letter::whereBetween('created_at', [
                    $date->copy()->startOfMonth(),
                    $date->copy()->endOfMonth(),
                ])->count();
            })
            ->values()
            ->all();

        // === PIE CHART ===
        $pieData = [
            'bebas'   => $totalBebas,
            'skripsi' => $totalSkripsi,
        ];

        // === RETURN SESUAI KEBUTUHAN BLADE ===
        return compact(
            'totalSurat',
            'totalBebas',
            'totalSkripsi',
            'hariIni',
            'chartLabels',
            'chartValues',
            'pieData'
        );
    }
}
