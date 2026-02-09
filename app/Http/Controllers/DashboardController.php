<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Dashboard\AdminDashboardService;
use App\Services\Dashboard\KepalaDashboardService;
use App\Services\Dashboard\StafDashboardService;
use App\Services\Dashboard\KaprodiDashboardService;
use App\Services\Dashboard\MahasiswaDashboardService;



class DashboardController extends Controller
{
    /**
     * Entry point dashboard
     * Routing dashboard berdasarkan prioritas role
     */
    public function dashboard(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            abort(401);
        }

        if ($user->hasRole('admin')) {
            return app()->call([$this, 'dashboardAdmin']);
        }

        if ($user->hasRole('kepala')) {
            return app()->call([$this, 'dashboardKepala']);
        }

        if ($user->hasRole('staf')) {
            return app()->call([$this, 'dashboardStaf']);
        }

        if ($user->hasRole('kaprodi')) {
            return app()->call([$this, 'dashboardKaprodi']);
        }

        if ($user->hasRole('mahasiswa')) {
            return app()->call([$this, 'dashboardMahasiswa']);
        }

        abort(403);
    }


    /**
     * Dashboard Mahasiswa
     */
    public function dashboardMahasiswa(MahasiswaDashboardService $service)
    {
        return view(
            'dashboard.mahasiswa',
            $service->summary(auth()->user())
        );
    }

    /**
     * Dashboard Staf
     */
    public function dashboardStaf(StafDashboardService $service)
    {
        return view(
            'dashboard.staf',
            $service->summary()
        );
    }

    /**
     * Dashboard Kepala Perpustakaan
     */
    public function dashboardKepala(KepalaDashboardService $service)
    {
        return view(
            'dashboard.kepala',
            $service->summary()
        );
    }

    /**
     * Dashboard Kaprodi
     */
    public function dashboardKaprodi(KaprodiDashboardService $service)
    {
        return view(
            'dashboard.kaprodi',
            $service->summary(auth()->user())
        );
    }

    /**
     * Dashboard Admin
     */
    public function dashboardAdmin(AdminDashboardService $service)
    {
        return view(
            'dashboard.admin',
            $service->summary()
        );
    }

    /**
     * Alias legacy / alternatif route
     */
    public function dashboards()
    {
        return $this->dashboard(request());
    }
}
