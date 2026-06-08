<?php

namespace App\Enums;

enum EquipmentType: string
{
    case Smartphone = 'smartphone';
    case Tablet     = 'tablet';
    case Laptop     = 'laptop';
    case Desktop    = 'desktop';
    case Printer    = 'printer';
    case Router     = 'router';
    case Switch     = 'switch';
    case Camera     = 'camera';
    case Other      = 'other';

    public function label(): string
    {
        return match($this) {
            self::Smartphone => 'Smartphone',
            self::Tablet     => 'Tablet',
            self::Laptop     => 'Laptop',
            self::Desktop    => 'Desktop',
            self::Printer    => 'Impresora',
            self::Router     => 'Router',
            self::Switch     => 'Switch',
            self::Camera     => 'Cámara',
            self::Other      => 'Otro',
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
