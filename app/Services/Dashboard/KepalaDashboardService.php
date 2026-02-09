<?php

namespace App\Services\Dashboard;

use App\Models\BebasPustakaRequest;

class KepalaDashboardService
{
    public function summary(): array
    {
        $menungguPersetujuan = BebasPustakaRequest::where('status', 'diverifikasi_staf')->count();

        $disetujuiTotal = BebasPustakaRequest::where('status', 'disetujui_kepala')->count();

        $recent = BebasPustakaRequest::where('status', 'diverifikasi_staf')
            ->orderByDesc('verified_at')
            ->take(5)
            ->get();

        return compact(
            'menungguPersetujuan',
            'disetujuiTotal',
            'recent'
        );
    }
}
