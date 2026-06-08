@extends('layouts.app')
@section('title','Historial de Asignaciones')
@section('page-title','Historial de Asignaciones')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0 fw-bold">Historial de Asignaciones</h5>
</div>
<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-6 col-md-3"><label class="form-label" style="font-size:.8rem">Desde</label><input type="date" name="from" class="form-control" value="{{ request('from') }}"></div>
            <div class="col-6 col-md-3"><label class="form-label" style="font-size:.8rem">Hasta</label><input type="date" name="to" class="form-control" value="{{ request('to') }}"></div>
            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill">Filtrar</button>
                <a href="{{ route('reports.assignment-history') }}" class="btn" style="background:var(--bg-hover);border:1px solid var(--border)"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table mb-0" style="font-size:.85rem">
            <thead><tr><th>Equipo</th><th>Colaborador</th><th>Entrega</th><th>Devolución</th><th>Estado</th><th>Asignado por</th></tr></thead>
            <tbody>
            @forelse($assignments as $a)
            <tr>
                <td><a href="{{ route('assignments.show',$a) }}" class="text-decoration-none" style="color:var(--text)">{{ $a->equipment->brand }} {{ $a->equipment->model }}</a><div style="font-size:.75rem;color:var(--text-muted)">{{ $a->equipment->serial_number }}</div></td>
                <td>{{ $a->collaborator->full_name }}</td>
                <td>{{ $a->assigned_at->format('d/m/Y') }}</td>
                <td>{{ $a->returned_at?->format('d/m/Y') ?? '—' }}</td>
                <td><span class="badge rounded-pill badge-{{ $a->status }}" style="font-size:.72rem">{{ $a->status === 'active' ? 'Activo' : ($a->status === 'returned' ? 'Devuelto' : 'Vencido') }}</span></td>
                <td>{{ $a->assignedBy->name }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center py-5" style="color:var(--text-muted)">Sin registros para el período seleccionado.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($assignments->hasPages())<div class="card-body pt-0">{{ $assignments->links() }}</div>@endif
</div>
@endsection
