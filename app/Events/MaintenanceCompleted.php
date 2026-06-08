<?php

namespace App\Events;

use App\Models\MaintenanceRecord;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MaintenanceCompleted
{
    use Dispatchable, SerializesModels;

    public function __construct(public readonly MaintenanceRecord $record) {}
}
