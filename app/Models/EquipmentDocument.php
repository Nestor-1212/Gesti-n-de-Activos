<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentDocument extends Model
{
    protected $fillable = ['equipment_id', 'name', 'path', 'filename', 'type', 'size'];

    public static array $types = [
        'invoice'  => 'Factura',
        'warranty' => 'Garantía',
        'contract' => 'Contrato',
        'manual'   => 'Manual',
        'other'    => 'Otro',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}
