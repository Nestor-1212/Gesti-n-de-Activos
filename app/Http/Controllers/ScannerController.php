<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScannerController extends Controller
{
    public function index(): View
    {
        return view('scanner.index');
    }

    public function lookup(Request $request): JsonResponse
    {
        $request->validate(['code' => ['required', 'string', 'max:100']]);

        $code = trim($request->code);

        $equipment = Equipment::with('activeAssignment.collaborator', 'mainPhoto')
            ->where('barcode', $code)
            ->orWhere('qr_code', $code)
            ->orWhere('serial_number', $code)
            ->orWhere('imei1', $code)
            ->first();

        if (! $equipment) {
            return response()->json(['found' => false, 'message' => 'Activo no encontrado.']);
        }

        return response()->json([
            'found'     => true,
            'equipment' => [
                'id'           => $equipment->id,
                'brand'        => $equipment->brand,
                'model'        => $equipment->model,
                'serial'       => $equipment->serial_number,
                'status'       => $equipment->status->value,
                'status_label' => $equipment->status_label,
                'assigned_to'  => $equipment->activeAssignment?->collaborator?->full_name,
                'url'          => route('equipment.show', $equipment),
            ],
        ]);
    }
}
