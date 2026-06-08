<?php

namespace App\Enums;

enum UserRole: string
{
    case SuperAdmin = 'super-admin';
    case Admin      = 'admin';
    case Supervisor = 'supervisor';
    case ReadOnly   = 'consulta';

    public function label(): string
    {
        return match($this) {
            self::SuperAdmin => 'Super Administrador',
            self::Admin      => 'Administrador',
            self::Supervisor => 'Supervisor',
            self::ReadOnly   => 'Consulta',
        };
    }

    /** Roles que pueden modificar datos */
    public function canWrite(): bool
    {
        return match($this) {
            self::SuperAdmin, self::Admin, self::Supervisor => true,
            default => false,
        };
    }

    /** Roles que pueden administrar usuarios */
    public function canManageUsers(): bool
    {
        return match($this) {
            self::SuperAdmin, self::Admin => true,
            default => false,
        };
    }
}
