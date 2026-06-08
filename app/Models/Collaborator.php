<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Collaborator extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'department_id', 'first_name', 'last_name', 'cedula', 'position',
        'email', 'phone', 'supervisor', 'photo', 'status',
    ];

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function activeAssignment()
    {
        return $this->hasOne(Assignment::class)->where('status', 'active');
    }

    public function equipment()
    {
        return $this->hasManyThrough(Equipment::class, Assignment::class, 'collaborator_id', 'id', 'id', 'equipment_id');
    }
}

