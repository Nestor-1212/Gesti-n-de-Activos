<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['name', 'code', 'description', 'active'];

    protected $casts = ['active' => 'boolean'];

    public function collaborators()
    {
        return $this->hasMany(Collaborator::class);
    }
}
