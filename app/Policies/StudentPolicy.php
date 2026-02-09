<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Student;

class StudentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'staf']);
    }

    public function view(User $user, Student $student): bool
    {
        return $user->hasAnyRole(['admin', 'staf']);
    }

    public function update(User $user, Student $student): bool
    {
        return $user->hasAnyRole(['admin', 'staf']);
    }

    public function assignUser(User $user, Student $student): bool
    {
        return $user->hasAnyRole(['admin', 'staf'])
            && is_null($student->user_id);
    }

    public function create(): bool
    {
        return false;
    }

    public function delete(): bool
    {
        return false;
    }
}

