<?php

namespace App\Policies;

use App\Enums\AssignmentStatus;
use App\Models\Assignment;
use App\Models\User;

class AssignmentPolicy
{
    public function before(User $user): ?bool
    {
        return $user->hasRole('super-admin') ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Assignment $assignment): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['admin', 'supervisor']);
    }

    public function update(User $user, Assignment $assignment): bool
    {
        return $user->hasRole(['admin', 'supervisor']);
    }

    public function return(User $user, Assignment $assignment): bool
    {
        return $user->hasRole(['admin', 'supervisor'])
            && $assignment->status === AssignmentStatus::Active;
    }

    public function delete(User $user, Assignment $assignment): bool
    {
        return $user->hasRole('admin');
    }

    public function printActa(User $user, Assignment $assignment): bool
    {
        return true;
    }
}
