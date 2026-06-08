<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReturnAssignmentRequest;
use App\Http\Requests\StoreAssignmentRequest;
use App\Models\Assignment;
use App\Models\Collaborator;
use App\Models\Equipment;
use App\Services\AssignmentService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssignmentController extends Controller
{
    public function __construct(private readonly AssignmentService $service) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Assignment::class);

        $assignments = Assignment::with('equipment', 'collaborator.department', 'assignedBy')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('search'), function ($q) use ($request) {
                $s = $request->search;
                $q->whereHas('collaborator', fn ($c) => $c->where('first_name', 'like', "%$s%")->orWhere('last_name', 'like', "%$s%"))
                  ->orWhereHas('equipment',    fn ($e) => $e->where('brand', 'like', "%$s%")->orWhere('serial_number', 'like', "%$s%"));
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('assignments.index', compact('assignments'));
    }

    public function create(Request $request): View
    {
        $this->authorize('create', Assignment::class);

        $equipment      = Equipment::available()->orderBy('brand')->get();
        $collaborators  = Collaborator::where('status', 'active')->orderBy('first_name')->get();
        $selectedEquipment = $request->filled('equipment_id')
            ? Equipment::find($request->equipment_id)
            : null;

        return view('assignments.create', compact('equipment', 'collaborators', 'selectedEquipment'));
    }

    public function store(StoreAssignmentRequest $request): RedirectResponse
    {
        try {
            $assignment = $this->service->assign($request->validated());
        } catch (\DomainException $e) {
            return back()->withErrors(['equipment_id' => $e->getMessage()])->withInput();
        }

        return redirect()->route('assignments.show', $assignment)
            ->with('success', 'Equipo asignado exitosamente.');
    }

    public function show(Assignment $assignment): View
    {
        $this->authorize('view', $assignment);

        $assignment->load('equipment.mainPhoto', 'collaborator.department', 'assignedBy');

        return view('assignments.show', compact('assignment'));
    }

    public function edit(Assignment $assignment): View
    {
        $this->authorize('update', $assignment);

        return view('assignments.edit', compact('assignment'));
    }

    public function update(Request $request, Assignment $assignment): RedirectResponse
    {
        $this->authorize('update', $assignment);

        $assignment->update($request->only('expected_return', 'observations'));

        return redirect()->route('assignments.show', $assignment)
            ->with('success', 'Asignación actualizada.');
    }

    public function destroy(Assignment $assignment): RedirectResponse
    {
        $this->authorize('delete', $assignment);

        $assignment->delete();

        return redirect()->route('assignments.index')->with('success', 'Asignación eliminada.');
    }

    public function returnEquipment(ReturnAssignmentRequest $request, Assignment $assignment): RedirectResponse
    {
        try {
            $this->service->return($assignment, $request->return_observations);
        } catch (\DomainException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->route('assignments.show', $assignment)
            ->with('success', 'Devolución registrada exitosamente.');
    }

    public function printActa(Assignment $assignment): mixed
    {
        $this->authorize('printActa', $assignment);

        $assignment->load('equipment.mainPhoto', 'collaborator.department', 'assignedBy');

        return Pdf::loadView('assignments.acta-pdf', compact('assignment'))
            ->download("acta-entrega-{$assignment->id}.pdf");
    }
}
