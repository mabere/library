<?php

namespace App\Observers;

use App\Models\BebasPustakaRequest;
use Illuminate\Support\Facades\Cache;

class BebasPustakaRequestObserver
{
    public function created(BebasPustakaRequest $request): void
    {
        $this->invalidateKaprodiDashboard();
    }

    public function updated(BebasPustakaRequest $request): void
    {
        // Hanya invalidate jika status berubah
        if ($request->wasChanged('status')) {
            $this->invalidateKaprodiDashboard();
        }
    }

    protected function invalidateKaprodiDashboard(): void
    {
        Cache::forgetPattern('dashboard.kaprodi.*');
    }
}
