<?php

namespace App\Services;

use App\Enums\EquipmentStatus;
use App\Models\ActivityLog;
use App\Models\Equipment;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class EquipmentService
{
    public function create(array $data, array $photos = []): Equipment
    {
        $data['barcode'] = 'EQ-' . strtoupper(Str::random(8));
        $data['qr_code'] = 'QR-' . strtoupper(Str::random(10));

        $equipment = Equipment::create($data);

        $this->storePhotos($equipment, $photos);

        ActivityLog::record(
            'create',
            "Activo registrado: {$equipment->brand} {$equipment->model} ({$equipment->serial_number})",
            $equipment
        );

        $this->flushDashboardCache();

        return $equipment;
    }

    public function update(Equipment $equipment, array $data, array $photos = []): Equipment
    {
        $old = $equipment->toArray();

        $equipment->update($data);

        $this->storePhotos($equipment, $photos);

        ActivityLog::record(
            'update',
            "Activo actualizado: {$equipment->brand} {$equipment->model}",
            $equipment,
            $old,
            $equipment->fresh()->toArray()
        );

        $this->flushDashboardCache();

        return $equipment;
    }

    public function delete(Equipment $equipment): void
    {
        ActivityLog::record(
            'delete',
            "Activo eliminado: {$equipment->brand} {$equipment->model} ({$equipment->serial_number})",
            $equipment
        );

        $equipment->delete();
        $this->flushDashboardCache();
    }

    private function flushDashboardCache(): void
    {
        Cache::forget('dashboard.stats');
        Cache::forget('dashboard.by_type');
        Cache::forget('dashboard.by_status');
    }

    public function generateQrSvg(Equipment $equipment): string
    {
        return QrCode::size(250)->generate(
            route('equipment.show', $equipment)
        );
    }

    public function generateQrPng(Equipment $equipment): string
    {
        return $this->generateQrSvg($equipment);
    }

    private function storePhotos(Equipment $equipment, array $photos): void
    {
        $isFirst = $equipment->photos()->doesntExist();

        foreach ($photos as $i => $photo) {
            /** @var UploadedFile $photo */
            $filename = sprintf('eq_%d_%d_%d.%s', $equipment->id, time(), $i, $photo->extension());
            $path = $photo->storeAs('equipment/photos', $filename, 'public');

            $equipment->photos()->create([
                'path'     => $path,
                'filename' => $filename,
                'is_main'  => ($isFirst && $i === 0),
            ]);
        }
    }
}
