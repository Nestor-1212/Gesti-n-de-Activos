<?php

namespace App\Http\Controllers;

use App\Enums\EquipmentStatus;
use App\Enums\MaintenanceStatus;
use App\Events\MaintenanceCompleted;
use App\Http\Requests\StoreMaintenanceRequest;
use App\Http\Requests\UpdateMaintenanceRequest;
use App\Models\ActivityLog;
use App\Models\Equipment;
use App\Models\MaintenanceRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MaintenanceController extends Controller
{
    public function index(Request $request): View
    {
        $records = MaintenanceRecord::with('equipment', 'user')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('maintenance.index', compact('records'));
    }

    public function create(Request $request): View
    {
        return view('maintenance.create', [
            'equipment' => Equipment::orderBy('brand')->get(),
            'selected'  => $request->filled('equipment_id') ? Equipment::find($request->equipment_id) : null,
        ]);
    }

    public function store(StoreMaintenanceRequest $request): RedirectResponse
    {
        $data = $request->validated() + ['user_id' => auth()->id()];

        $record = MaintenanceRecord::create($data);

        if ($record->status !== MaintenanceStatus::Completed->value) {
            $record->equipment->update(['status' => EquipmentStatus::Maintenance]);
        }

        ActivityLog::record('maintenance', "Mantenimiento registrado para {$record->equipment->brand} {$record->equipment->model}", $record);

        return redirect()->route('maintenance.show', $record)
            ->with('success', 'Mantenimiento registrado.');
    }

    public function show(MaintenanceRecord $maintenance): View
    {
        $maintenance->load('equipment', 'user');

        return view('maintenance.show', compact('maintenance'));
    }

    public function edit(MaintenanceRecord $maintenance): View
    {
        return view('maintenance.edit', [
            'maintenance' => $maintenance,
            'equipment'   => Equipment::orderBy('brand')->get(),
        ]);
    }

    public function update(UpdateMaintenanceRequest $request, MaintenanceRecord $maintenance): RedirectResponse
    {
        $wasCompleted = $maintenance->status === MaintenanceStatus::Completed->value;

        $maintenance->update($request->validated());

        $isNowCompleted = $maintenance->status === MaintenanceStatus::Completed->value;

        if ($isNowCompleted && ! $wasCompleted) {
            $maintenance->equipment->update(['status' => EquipmentStatus::Available]);
            event(new MaintenanceCompleted($maintenance));
        }

        ActivityLog::record('update', "Mantenimiento actualizado para {$maintenance->equipment->brand} {$maintenance->equipment->model}", $maintenance);

        return redirect()->route('maintenance.show', $maintenance)
            ->with('success', 'Mantenimiento actualizado.');
    }

    public function destroy(MaintenanceRecord $maintenance): RedirectResponse
    {
        $maintenance->delete();

        return redirect()->route('maintenance.index')->with('success', 'Registro eliminado.');
    }
}
