<?php

namespace App\Services\Skripsi;

use App\Models\SkripsiRequest;
use App\Enums\SkripsiStatus;
use DomainException;

class StafSkripsiService
{
    public function ensureCanVerify(SkripsiRequest $req): void
    {
        if ($req->status !== SkripsiStatus::DIAJUKAN->value) {
            throw new DomainException('Pengajuan sudah diproses atau tidak valid.');
        }
    }

    public function verify(SkripsiRequest $req, int $stafUserId, bool $isComplete, ?string $note = null ): SkripsiRequest
    {
        $this->ensureCanVerify($req);

        $toStatus = $isComplete
            ? SkripsiStatus::DIVERIFIKASI_STAF
            : SkripsiStatus::DITOLAK_STAF;

        $req->update([
            'status'         => $toStatus->value,
            'verified_by'    => $stafUserId,
            'verified_at'    => now(),
            'rejection_note' => $isComplete ? null : ($note ?: 'Berkas belum lengkap.'),
        ]);

        return $req;
    }

    public function reject(SkripsiRequest $req, int $stafUserId, string $rejectionNote ): SkripsiRequest
    {
        $this->ensureCanVerify($req);

        $req->update([
            'status'         => SkripsiStatus::DITOLAK_STAF->value,
            'verified_by'    => $stafUserId,
            'verified_at'    => now(),
            'rejection_note' => $rejectionNote,
        ]);

        return $req;
    }
}
