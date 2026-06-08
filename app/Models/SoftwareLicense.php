<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoftwareLicense extends Model
{
    protected $fillable = [
        'equipment_id', 'software_name', 'license_key', 'version',
        'expiry_date', 'vendor', 'cost', 'status', 'notes',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'cost' => 'decimal:2',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}
