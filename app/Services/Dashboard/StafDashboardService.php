<?php

namespace App\Services\Dashboard;

use App\Models\BebasPustakaRequest;

class StafDashboardService
{
    public function summary(): array
    {
        $menungguVerifikasi = BebasPustakaRequest::where('status', 'diajukan')->count();

        $verifikasiHariIni = BebasPustakaRequest::whereDate('verified_at', today())->count();

        $ditolakTotal = BebasPustakaRequest::where('status', 'ditolak_staf')->count();

        $recent = BebasPustakaRequest::orderByDesc('created_at')
            ->take(5)
            ->get();

        return compact(
            'menungguVerifikasi',
            'verifikasiHariIni',
            'ditolakTotal',
            'recent'
        );
    }
}
