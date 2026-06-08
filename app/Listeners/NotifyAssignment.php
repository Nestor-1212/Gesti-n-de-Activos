<?php

namespace App\Listeners;

use App\Events\EquipmentAssigned;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class NotifyAssignment
{
    public function handle(EquipmentAssigned $event): void
    {
        $assignment = $event->assignment;

        // Notify all admins about the new assignment
        User::role(['super-admin', 'admin'])
            ->where('id', '!=', auth()->id())
            ->each(function (User $admin) use ($assignment) {
                Notification::create([
                    'user_id'    => $admin->id,
                    'title'      => 'Nueva asignación de activo',
                    'message'    => sprintf(
                        '%s asignó el activo %s %s a %s.',
                        auth()->user()->name,
                        $assignment->equipment->brand,
                        $assignment->equipment->model,
                        $assignment->collaborator->full_name
                    ),
                    'type'       => 'general',
                    'model_type' => \App\Models\Assignment::class,
                    'model_id'   => $assignment->id,
                ]);
            });

        Log::info('Equipment assigned', [
            'equipment_id'    => $assignment->equipment_id,
            'collaborator_id' => $assignment->collaborator_id,
            'assigned_by'     => $assignment->assigned_by,
        ]);
    }
}
