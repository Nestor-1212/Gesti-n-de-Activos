<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceRecord extends Model
{
    protected $fillable = [
        'equipment_id', 'user_id', 'type', 'description',
        'maintenance_date', 'completed_date', 'technician', 'cost', 'status', 'notes',
    ];

    protected $casts = [
        'maintenance_date' => 'date',
        'completed_date'   => 'date',
        'cost'             => 'decimal:2',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
