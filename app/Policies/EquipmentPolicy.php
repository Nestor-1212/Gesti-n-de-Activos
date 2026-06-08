<?php

namespace App\Policies;

use App\Models\Equipment;
use App\Models\User;

class EquipmentPolicy
{
    /** Super-admin bypasses all checks */
    public function before(User $user): ?bool
    {
        return $user->hasRole('super-admin') ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can list equipment
    }

    public function view(User $user, Equipment $equipment): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['admin', 'supervisor']);
    }

    public function update(User $user, Equipment $equipment): bool
    {
        return $user->hasRole(['admin', 'supervisor']);
    }

    public function delete(User $user, Equipment $equipment): bool
    {
        return $user->hasRole('admin');
    }
}
