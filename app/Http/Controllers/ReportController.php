<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\InventoryExport;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function inventory(Request $request)
    {
        $query = \App\Models\Equipment::with('mainPhoto', 'activeAssignment.collaborator.department');

        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('type'))   $query->where('type', $request->type);

        $equipment = $query->orderBy('brand')->get();

        if ($request->format === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.pdf.inventory', compact('equipment'));
            return $pdf->download('inventario-general.pdf');
        }

        if ($request->format === 'excel') {
            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\InventoryExport($equipment), 'inventario-general.xlsx');
        }

        return view('reports.inventory', compact('equipment'));
    }

    public function byCollaborator(Request $request)
    {
        $collaborators = \App\Models\Collaborator::with(['assignments' => fn($q) => $q->where('status', 'active')->with('equipment')])->where('status', 'active')->get();

        if ($request->format === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.pdf.by-collaborator', compact('collaborators'));
            return $pdf->download('equipos-por-colaborador.pdf');
        }

        return view('reports.by-collaborator', compact('collaborators'));
    }

    public function byDepartment(Request $request)
    {
        $departments = \App\Models\Department::with(['collaborators.assignments' => fn($q) => $q->where('status', 'active')->with('equipment')])->get();

        if ($request->format === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.pdf.by-department', compact('departments'));
            return $pdf->download('equipos-por-departamento.pdf');
        }

        return view('reports.by-department', compact('departments'));
    }

    public function warrantyExpiring(Request $request)
    {
        $days = $request->days ?? 30;
        $equipment = \App\Models\Equipment::whereNotNull('warranty_expiry')
            ->where('warranty_expiry', '>=', now())
            ->where('warranty_expiry', '<=', now()->addDays($days))
            ->with('activeAssignment.collaborator')
            ->orderBy('warranty_expiry')
            ->get();

        if ($request->format === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.pdf.warranty', compact('equipment', 'days'));
            return $pdf->download('garantias-por-vencer.pdf');
        }

        return view('reports.warranty-expiring', compact('equipment', 'days'));
    }

    public function assignmentHistory(Request $request)
    {
        $assignments = \App\Models\Assignment::with('equipment', 'collaborator', 'assignedBy')
            ->when($request->filled('from'), fn($q) => $q->where('assigned_at', '>=', $request->from))
            ->when($request->filled('to'),   fn($q) => $q->where('assigned_at', '<=', $request->to))
            ->latest()->paginate(20)->withQueryString();

        return view('reports.assignment-history', compact('assignments'));
    }
}
