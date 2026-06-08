<?php

namespace App\Enums;

enum AssignmentStatus: string
{
    case Active   = 'active';
    case Returned = 'returned';
    case Overdue  = 'overdue';

    public function label(): string
    {
        return match($this) {
            self::Active   => 'Activo',
            self::Returned => 'Devuelto',
            self::Overdue  => 'Vencido',
        };
    }
}
