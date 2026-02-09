<?php

namespace App\Services\BebasPustaka;

use App\Models\BebasPustakaRequest;
use App\Enums\BebasPustakaStatus;
use DomainException;

class StafBebasPustakaService
{
    public function ensureCanVerify(BebasPustakaRequest $req): void
    {
        if ($req->status !== BebasPustakaStatus::DIAJUKAN->value) {
            throw new DomainException('Pengajuan sudah diproses atau tidak valid.');
        }
    }

    public function verify(
        BebasPustakaRequest $req,
        int $stafUserId,
        bool $hasFine,
        ?string $fineNote = null
    ): BebasPustakaRequest {
        $this->ensureCanVerify($req);

        $toStatus = $hasFine
            ? BebasPustakaStatus::DITOLAK_STAF
            : BebasPustakaStatus::DIVERIFIKASI_STAF;

        $req->update([
            'has_fine'       => $hasFine,
            'fine_note'      => $fineNote,
            'status'         => $toStatus->value,
            'verified_by'    => $stafUserId,
            'verified_at'    => now(),
            'rejection_note' => $hasFine ? ($fineNote ?: 'Masih ada tanggungan.') : null,
        ]);

        return $req;
    }

    public function reject(
        BebasPustakaRequest $req,
        int $stafUserId,
        string $rejectionNote
    ): BebasPustakaRequest {
        $this->ensureCanVerify($req);

        $req->update([
            'status'         => BebasPustakaStatus::DITOLAK_STAF->value,
            'rejection_note' => $rejectionNote,
            'verified_by'    => $stafUserId,
            'verified_at'    => now(),
        ]);

        return $req;
    }
}
