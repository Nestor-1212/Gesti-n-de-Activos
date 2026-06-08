<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Models\ActivityLog;
use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    public function index(): View
    {
        $departments = Department::withCount('collaborators')->orderBy('name')->paginate(15);

        return view('departments.index', compact('departments'));
    }

    public function create(): View
    {
        return view('departments.create');
    }

    public function store(StoreDepartmentRequest $request): RedirectResponse
    {
        $department = Department::create($request->validated() + ['active' => $request->boolean('active', true)]);

        ActivityLog::record('create', "Departamento creado: {$department->name}", $department);

        return redirect()->route('departments.index')->with('success', 'Departamento creado exitosamente.');
    }

    public function show(Department $department): View
    {
        $department->load('collaborators');

        return view('departments.show', compact('department'));
    }

    public function edit(Department $department): View
    {
        return view('departments.edit', compact('department'));
    }

    public function update(UpdateDepartmentRequest $request, Department $department): RedirectResponse
    {
        $department->update($request->validated() + ['active' => $request->boolean('active')]);

        ActivityLog::record('update', "Departamento actualizado: {$department->name}", $department);

        return redirect()->route('departments.index')->with('success', 'Departamento actualizado.');
    }

    public function destroy(Department $department): RedirectResponse
    {
        if ($department->collaborators()->exists()) {
            return back()->withErrors(['error' => 'No se puede eliminar un departamento con colaboradores activos.']);
        }

        ActivityLog::record('delete', "Departamento eliminado: {$department->name}", $department);

        $department->delete();

        return redirect()->route('departments.index')->with('success', 'Departamento eliminado.');
    }
}
