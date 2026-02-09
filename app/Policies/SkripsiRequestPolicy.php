<?php

namespace App\Policies;

use App\Models\User;
use App\Models\SkripsiRequest;
use App\Enums\SkripsiStatus;

class SkripsiRequestPolicy
{
    /**
     * Siapa boleh melihat daftar
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole([
            'admin', 'staf', 'kepala', 'kaprodi', 'mahasiswa'
        ]);
    }

    /**
     * Siapa boleh melihat detail
     */
    public function view(User $user, SkripsiRequest $req): bool
    {
        if ($user->hasAnyRole(['admin', 'staf', 'kepala'])) {
            return true;
        }

        if ($user->hasRole('kaprodi')) {
            return $req->student
                && in_array(
                    $req->student->department_id,
                    $user->departmentIdsForRole('kaprodi'),
                    true
                );
        }

        if ($user->hasRole('mahasiswa')) {
            return (int) $req->user_id === (int) $user->id;
        }

        return false;
    }

    /**
     * Mahasiswa boleh mengajukan
     */
    public function create(User $user): bool
    {
        return $user->hasRole('mahasiswa');
    }

    /**
     * Staf boleh verifikasi
     */
    public function verify(User $user, SkripsiRequest $req): bool
    {
        return $user->hasRole('staf')
            && $req->status === SkripsiStatus::DIAJUKAN->value;
    }

    /**
     * Kepala boleh menyetujui
     */
    public function approve(User $user, SkripsiRequest $req): bool
    {
        return $user->hasRole('kepala')
            && $req->status === SkripsiStatus::DIVERIFIKASI_STAF->value;
    }

    /**
     * Siapa boleh download surat
     */
    public function download(User $user, SkripsiRequest $req): bool
    {
        if (!$req->letter_id) {
            return false;
        }

        return $this->view($user, $req);
    }
}
