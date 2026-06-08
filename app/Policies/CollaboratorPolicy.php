<?php

namespace App\Policies;

use App\Models\Collaborator;
use App\Models\User;

class CollaboratorPolicy
{
    public function before(User $user): ?bool
    {
        return $user->hasRole('super-admin') ? true : null;
    }

    public function viewAny(User $user): bool { return true; }
    public function view(User $user, Collaborator $collaborator): bool { return true; }
    public function create(User $user): bool { return $user->hasRole(['admin', 'supervisor']); }
    public function update(User $user, Collaborator $collaborator): bool { return $user->hasRole(['admin', 'supervisor']); }
    public function delete(User $user, Collaborator $collaborator): bool { return $user->hasRole('admin'); }
}
