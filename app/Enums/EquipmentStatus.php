<?php

namespace App\Enums;

enum EquipmentStatus: string
{
    case Available   = 'available';
    case Assigned    = 'assigned';
    case Maintenance = 'maintenance';
    case Damaged     = 'damaged';
    case Lost        = 'lost';
    case Retired     = 'retired';

    public function label(): string
    {
        return match($this) {
            self::Available   => 'Disponible',
            self::Assigned    => 'Asignado',
            self::Maintenance => 'En Reparación',
            self::Damaged     => 'Dañado',
            self::Lost        => 'Extraviado',
            self::Retired     => 'Retirado',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::Available   => 'badge-available',
            self::Assigned    => 'badge-assigned',
            self::Maintenance => 'badge-maintenance',
            self::Damaged     => 'badge-damaged',
            self::Lost        => 'badge-lost',
            self::Retired     => 'badge-retired',
        };
    }

    public static function labels(): array
    {
        return array_column(
            array_map(fn($case) => ['key' => $case->value, 'label' => $case->label()], self::cases()),
            'label',
            'key'
        );
    }
}
