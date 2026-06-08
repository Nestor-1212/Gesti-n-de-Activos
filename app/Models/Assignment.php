<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = [
        'equipment_id', 'collaborator_id', 'assigned_by',
        'assigned_at', 'expected_return', 'returned_at',
        'reason', 'observations', 'return_observations',
        'signature_path', 'status',
    ];

    protected $casts = [
        'assigned_at'     => 'date',
        'expected_return' => 'date',
        'returned_at'     => 'date',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function collaborator()
    {
        return $this->belongsTo(Collaborator::class);
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
