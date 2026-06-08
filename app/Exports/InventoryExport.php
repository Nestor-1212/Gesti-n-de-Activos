<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Models\Equipment;

class InventoryExport implements FromCollection, WithHeadings, WithMapping
{
    protected $equipment;

    public function __construct($equipment = null)
    {
        $this->equipment = $equipment ?? Equipment::with('activeAssignment.collaborator')->get();
    }

    public function collection()
    {
        return $this->equipment;
    }

    public function headings(): array
    {
        return ['ID', 'Marca', 'Modelo', 'Serie', 'IMEI1', 'Tipo', 'Estado', 'Compañía', 'S.O.', 'RAM', 'Almacenamiento', 'Fecha Compra', 'Garantía', 'Proveedor', 'Costo', 'Asignado A'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->brand,
            $row->model,
            $row->serial_number,
            $row->imei1,
            $row->type_label,
            $row->status_label,
            $row->carrier,
            $row->operating_system,
            $row->ram,
            $row->storage_capacity,
            $row->purchase_date?->format('d/m/Y'),
            $row->warranty_expiry?->format('d/m/Y'),
            $row->supplier,
            $row->cost,
            $row->activeAssignment?->collaborator?->full_name,
        ];
    }
}
