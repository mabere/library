<?php

namespace App\Services\Dashboard;

use App\Models\User;
use App\Models\SkripsiRequest;
use App\Models\BebasPustakaRequest;

class MahasiswaDashboardService
{
    public function summary(User $user): array
    {
        $sudahDisetujui = BebasPustakaRequest::where('user_id', $user->id)
            ->where('status', 'disetujui_kepala')
            ->exists();

        $sedangDiproses = BebasPustakaRequest::where('user_id', $user->id)
            ->whereIn('status', ['diajukan', 'diverifikasi_staf'])
            ->exists();

        $totalPengajuan = BebasPustakaRequest::where('user_id', $user->id)->count();

        $menunggu = BebasPustakaRequest::where('user_id', $user->id)
            ->where('status', 'diajukan')
            ->count();

        $ditolak = BebasPustakaRequest::where('user_id', $user->id)
            ->where('status', 'ditolak_staf')
            ->count();

        $disetujui = BebasPustakaRequest::where('user_id', $user->id)
            ->where('status', 'disetujui_kepala')
            ->count();

        $recent = BebasPustakaRequest::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return compact(
            'sudahDisetujui',
            'sedangDiproses',
            'totalPengajuan',
            'menunggu',
            'ditolak',
            'disetujui',
            'recent'
        );
    }
}
