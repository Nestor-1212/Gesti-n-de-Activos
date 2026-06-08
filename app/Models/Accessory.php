<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Accessory extends Model
{
    protected $fillable = ['equipment_id', 'name', 'type', 'brand', 'serial_number', 'status', 'notes'];

    public static array $types = [
        'charger'   => 'Cargador',
        'case'      => 'Funda',
        'headphones'=> 'Audífonos',
        'sim'       => 'SIM',
        'cable'     => 'Cable',
        'keyboard'  => 'Teclado',
        'mouse'     => 'Mouse',
        'other'     => 'Otro',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}
