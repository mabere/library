<?php

namespace App\Services\Dashboard;

use App\Models\User;
use App\Models\Letter;
use App\Models\SkripsiRequest;
use App\Models\BebasPustakaRequest;
use Illuminate\Support\Facades\Cache;
use App\Support\DepartmentScopedQuery;

class KaprodiDashboardService
{
    public function summary(User $user): array
    {
        $departmentIds = $user->departmentIdsForRole('kaprodi');
        sort($departmentIds);

        $cacheKey = 'dashboard.kaprodi.'
            . $user->id . '.'
            . implode('-', $departmentIds);

        return Cache::remember(
            $cacheKey,
            now()->addMinutes(5),
            fn () => $this->buildSummary($user)
        );
    }

    protected function buildSummary(User $user): array
    {
        // === BASE QUERY ===
        $bebasBase = DepartmentScopedQuery::apply(
            BebasPustakaRequest::query(),
            $user
        );

        $skripsiBase = DepartmentScopedQuery::apply(
            SkripsiRequest::query(),
            $user
        );

        // === HITUNG BEBAS ===
        $bebas = [
            'total'      => (clone $bebasBase)->count(),
            'menunggu'   => (clone $bebasBase)->where('status', 'diajukan')->count(),
            'verifikasi' => (clone $bebasBase)->where('status', 'diverifikasi_staf')->count(),
            'ditolak'    => (clone $bebasBase)->where('status', 'ditolak_staf')->count(),
            'disetujui'  => (clone $bebasBase)->where('status', 'disetujui_kepala')->count(),
        ];

        // === HITUNG SKRIPSI ===
        $skripsi = [
            'total'      => (clone $skripsiBase)->count(),
            'menunggu'   => (clone $skripsiBase)->where('status', 'diajukan')->count(),
            'verifikasi' => (clone $skripsiBase)->where('status', 'diverifikasi_staf')->count(),
            'ditolak'    => (clone $skripsiBase)->where('status', 'ditolak_staf')->count(),
            'disetujui'  => (clone $skripsiBase)->where('status', 'disetujui_kepala')->count(),
        ];

        // === AGREGASI ===
        $totalPengajuan = $bebas['total'] + $skripsi['total'];
        $menunggu       = $bebas['menunggu'] + $skripsi['menunggu'];
        $diverifikasi   = $bebas['verifikasi'] + $skripsi['verifikasi'];
        $ditolak        = $bebas['ditolak'] + $skripsi['ditolak'];
        $disetujui      = $bebas['disetujui'] + $skripsi['disetujui'];

        // === SURAT ===
        $letterBase = DepartmentScopedQuery::apply(
            Letter::query(),
            $user
        );

        $totalSurat   = (clone $letterBase)->count();
        $totalBebas   = (clone $letterBase)
            ->where('letter_type', 'bebas_pustaka')
            ->count();
        $totalSkripsi = (clone $letterBase)
            ->where('letter_type', 'penyerahan_skripsi')
            ->count();

        // === RECENT ===
        $recent = $this->recentRequests($user);

        return compact(
            'totalPengajuan',
            'menunggu',
            'diverifikasi',
            'ditolak',
            'disetujui',
            'totalSurat',
            'totalBebas',
            'totalSkripsi',
            'recent'
        );
    }


    private function recentRequests(User $user, int $limit = 5)
    {
        $bebas = DepartmentScopedQuery::apply(
            BebasPustakaRequest::query(),
            $user
        )
            ->select(
                'id',
                'nim',
                'nama',
                'status',
                'submitted_at'
            )
            ->addSelect(\DB::raw("'bebas_pustaka' as type"));

        $skripsi = DepartmentScopedQuery::apply(
            SkripsiRequest::query(),
            $user
        )
            ->select(
                'id',
                'nim',
                'nama',
                'status',
                'submitted_at'
            )
            ->addSelect(\DB::raw("'penyerahan_skripsi' as type"));

        return $bebas
            ->unionAll($skripsi)
            ->orderByDesc('submitted_at')
            ->limit($limit)
            ->get();
    }

}
