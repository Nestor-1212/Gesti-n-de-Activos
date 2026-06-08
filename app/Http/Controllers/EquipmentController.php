<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEquipmentRequest;
use App\Http\Requests\UpdateEquipmentRequest;
use App\Models\Equipment;
use App\Services\EquipmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\View\View;

class EquipmentController extends Controller
{
    public function __construct(private readonly EquipmentService $service) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Equipment::class);

        $equipment = Equipment::with('mainPhoto', 'activeAssignment.collaborator')
            ->when($request->filled('search'), fn ($q) => $q->search($request->search))
            ->when($request->filled('status'), fn ($q) => $q->byStatus($request->status))
            ->when($request->filled('type'),   fn ($q) => $q->where('type', $request->type))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('equipment.index', compact('equipment'));
    }

    public function create(): View
    {
        $this->authorize('create', Equipment::class);

        return view('equipment.create');
    }

    public function store(StoreEquipmentRequest $request): RedirectResponse
    {
        $equipment = $this->service->create(
            $request->except('photos'),
            $request->file('photos', [])
        );

        return redirect()->route('equipment.show', $equipment)
            ->with('success', 'Activo registrado exitosamente.');
    }

    public function show(Equipment $equipment): View
    {
        $this->authorize('view', $equipment);

        $equipment->load([
            'photos',
            'documents',
            'accessories',
            'softwareLicenses',
            'maintenanceRecords.user',
            'assignments.collaborator',
            'assignments.assignedBy',
            'activeAssignment.collaborator',
        ]);

        $qrCode = base64_encode($this->service->generateQrPng($equipment));

        return view('equipment.show', compact('equipment', 'qrCode'));
    }

    public function edit(Equipment $equipment): View
    {
        $this->authorize('update', $equipment);

        return view('equipment.edit', compact('equipment'));
    }

    public function update(UpdateEquipmentRequest $request, Equipment $equipment): RedirectResponse
    {
        $this->service->update(
            $equipment,
            $request->except('photos'),
            $request->file('photos', [])
        );

        return redirect()->route('equipment.show', $equipment)
            ->with('success', 'Activo actualizado exitosamente.');
    }

    public function destroy(Equipment $equipment): RedirectResponse
    {
        $this->authorize('delete', $equipment);

        $this->service->delete($equipment);

        return redirect()->route('equipment.index')
            ->with('success', 'Activo eliminado.');
    }

    public function generateQr(Equipment $equipment): HttpResponse
    {
        $this->authorize('view', $equipment);

        return response($this->service->generateQrPng($equipment))
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', "attachment; filename=\"qr-{$equipment->barcode}.png\"");
    }
}
