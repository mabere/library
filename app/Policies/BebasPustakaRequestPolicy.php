<?php

namespace App\Policies;

use App\Models\BebasPustakaRequest;
use App\Models\User;

class BebasPustakaRequestPolicy
{
    public function download(User $user, BebasPustakaRequest $req): bool
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

}
