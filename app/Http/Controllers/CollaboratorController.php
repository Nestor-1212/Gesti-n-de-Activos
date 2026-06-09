<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCollaboratorRequest;
use App\Http\Requests\UpdateCollaboratorRequest;
use App\Models\ActivityLog;
use App\Models\Collaborator;
use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CollaboratorController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Collaborator::class);

        $collaborators = Collaborator::with(['department', 'assignments' => fn ($q) => $q->where('status', 'active')])
            ->when($request->filled('search'), function ($q) use ($request) {
                $s = $request->search;
                $q->where(fn ($q) => $q
                    ->where('first_name', 'like', "%$s%")
                    ->orWhere('last_name',  'like', "%$s%")
                    ->orWhere('cedula',     'like', "%$s%")
                    ->orWhere('email',      'like', "%$s%")
                );
            })
            ->when($request->filled('department'), fn ($q) => $q->where('department_id', $request->department))
            ->when($request->filled('status'),     fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $departments = Department::where('active', true)->orderBy('name')->get();

        return view('collaborators.index', compact('collaborators', 'departments'));
    }

    public function create(): View
    {
        $this->authorize('create', Collaborator::class);

        return view('collaborators.create', [
            'departments' => Department::where('active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(StoreCollaboratorRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('collaborators', 'public');
        }

        $collaborator = Collaborator::create($data);

        ActivityLog::record('create', "Colaborador registrado: {$collaborator->full_name}", $collaborator);

        return redirect()->route('collaborators.show', $collaborator)
            ->with('success', 'Colaborador registrado exitosamente.');
    }

    public function show(Collaborator $collaborator): View
    {
        $this->authorize('view', $collaborator);

        $collaborator->load('department', 'assignments.equipment.mainPhoto', 'assignments.assignedBy');

        return view('collaborators.show', compact('collaborator'));
    }

    public function edit(Collaborator $collaborator): View
    {
        $this->authorize('update', $collaborator);

        return view('collaborators.edit', [
            'collaborator' => $collaborator,
            'departments'  => Department::where('active', true)->orderBy('name')->get(),
        ]);
    }

    public function update(UpdateCollaboratorRequest $request, Collaborator $collaborator): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('collaborators', 'public');
        }

        $collaborator->update($data);

        ActivityLog::record('update', "Colaborador actualizado: {$collaborator->full_name}", $collaborator);

        return redirect()->route('collaborators.show', $collaborator)
            ->with('success', 'Colaborador actualizado exitosamente.');
    }

    public function destroy(Collaborator $collaborator): RedirectResponse
    {
        $this->authorize('delete', $collaborator);

        ActivityLog::record('delete', "Colaborador eliminado: {$collaborator->full_name}", $collaborator);

        $collaborator->delete();

        return redirect()->route('collaborators.index')->with('success', 'Colaborador eliminado.');
    }
}
