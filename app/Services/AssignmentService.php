<?php

namespace App\Services;

use App\Enums\AssignmentStatus;
use App\Enums\EquipmentStatus;
use App\Events\EquipmentAssigned;
use App\Events\EquipmentReturned;
use App\Models\ActivityLog;
use App\Models\Assignment;
use App\Models\Equipment;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;

class AssignmentService
{
    /**
     * @throws \Throwable
     */
    public function assign(array $data): Assignment
    {
        return DB::transaction(function () use ($data) {
            $equipment = Equipment::lockForUpdate()->findOrFail($data['equipment_id']);

            if ($equipment->status !== EquipmentStatus::Available) {
                throw new \DomainException('El equipo no está disponible para asignación.');
            }

            $assignment = Assignment::create([
                ...$data,
                'assigned_by' => auth()->id(),
                'status'      => AssignmentStatus::Active,
            ]);

            $equipment->update(['status' => EquipmentStatus::Assigned]);

            $this->scheduleReturnNotification($assignment);

            ActivityLog::record(
                'assign',
                "Activo {$equipment->brand} {$equipment->model} asignado a {$assignment->collaborator->full_name}",
                $assignment
            );

            event(new EquipmentAssigned($assignment));

            return $assignment;
        });
    }

    /**
     * @throws \Throwable
     */
    public function return(Assignment $assignment, ?string $observations = null): Assignment
    {
        return DB::transaction(function () use ($assignment, $observations) {
            if ($assignment->status !== AssignmentStatus::Active) {
                throw new \DomainException('Esta asignación ya fue procesada.');
            }

            $assignment->update([
                'status'              => AssignmentStatus::Returned,
                'returned_at'         => now(),
                'return_observations' => $observations,
            ]);

            $assignment->equipment->update(['status' => EquipmentStatus::Available]);

            ActivityLog::record(
                'return',
                "Activo {$assignment->equipment->brand} {$assignment->equipment->model} devuelto por {$assignment->collaborator->full_name}",
                $assignment
            );

            event(new EquipmentReturned($assignment));

            return $assignment;
        });
    }

    private function scheduleReturnNotification(Assignment $assignment): void
    {
        if (! $assignment->expected_return) {
            return;
        }

        Notification::create([
            'user_id'    => auth()->id(),
            'title'      => 'Devolución programada',
            'message'    => sprintf(
                'El activo %s %s asignado a %s debe devolverse el %s.',
                $assignment->equipment->brand,
                $assignment->equipment->model,
                $assignment->collaborator->full_name,
                $assignment->expected_return->format('d/m/Y')
            ),
            'type'       => 'return',
            'model_type' => Assignment::class,
            'model_id'   => $assignment->id,
        ]);
    }
}
