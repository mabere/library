<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class DepartmentScopedQuery
{
    /**
     * Scope query berdasarkan department kaprodi.
     * Admin / staf / kepala -> tidak difilter
     */
    public static function apply(
        Builder $query,
        User $user,
        string $relation = 'student'
    ): Builder {
        // Role UPT -> full access
        if ($user->hasAnyRole(['admin', 'staf', 'kepala'])) {
            return $query;
        }

        // Kaprodi -> filter department mahasiswa
        if ($user->hasRole('kaprodi')) {
            $departmentIds = $user->departmentIdsForRole('kaprodi');

            return $query->whereHas($relation, function ($q) use ($departmentIds) {
                $q->whereIn('department_id', $departmentIds);
            });
        }

        // Mahasiswa -> data sendiri
        if ($user->hasRole('mahasiswa')) {
            return $query->where('user_id', $user->id);
        }

        return $query;
    }
}
