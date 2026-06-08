<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentPhoto extends Model
{
    protected $fillable = ['equipment_id', 'path', 'filename', 'is_main'];
    protected $casts = ['is_main' => 'boolean'];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}
