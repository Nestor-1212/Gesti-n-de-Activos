@extends('layouts.app')
@section('title','Mantenimiento')
@section('page-title','Registros de Mantenimiento')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0 fw-bold">Mantenimiento</h5>
    <a href="{{ route('maintenance.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Nuevo Mantenimiento</a>
</div>
<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-6 col-md-4">
                <select name="status" class="form-select">
                    <option value="">Todos los estados</option>
                    <option value="pending" {{ request('status')==='pending'?'selected':'' }}>Pendiente</option>
                    <option value="in_progress" {{ request('status')==='in_progress'?'selected':'' }}>En Proceso</option>
                    <option value="completed" {{ request('status')==='completed'?'selected':'' }}>Completado</option>
                </select>
            </div>
            <div class="col-6 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill">Filtrar</button>
                <a href="{{ route('maintenance.index') }}" class="btn" style="background:var(--bg-hover);border:1px solid var(--border)"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Equipo</th><th>Tipo</th><th>Descripción</th><th>Fecha</th><th>Técnico</th><th>Estado</th><th>Costo</th><th width="80">Acciones</th></tr></thead>
            <tbody>
            @forelse($records as $m)
            @php $typeLabels=['preventive'=>'Preventivo','corrective'=>'Correctivo','upgrade'=>'Actualización']; $statusBg=['pending'=>'rgba(249,115,22,.15)','in_progress'=>'rgba(45,212,191,.15)','completed'=>'rgba(34,197,94,.15)']; $statusColor=['pending'=>'#f97316','in_progress'=>'#2dd4bf','completed'=>'#22c55e']; $statusLabel=['pending'=>'Pendiente','in_progress'=>'En Proceso','completed'=>'Completado']; @endphp
            <tr>
                <td>
                    <div class="fw-600" style="font-size:.9rem">{{ $m->equipment->brand }} {{ $m->equipment->model }}</div>
                    <div style="font-size:.75rem;color:var(--text-muted)">{{ $m->equipment->serial_number }}</div>
                </td>
                <td><span class="badge rounded-pill" style="background:rgba(249,115,22,.15);color:#f97316;font-size:.75rem">{{ $typeLabels[$m->type] ?? $m->type }}</span></td>
                <td style="font-size:.85rem">{{ Str::limit($m->description,60) }}</td>
                <td style="font-size:.85rem">{{ $m->maintenance_date->format('d/m/Y') }}</td>
                <td style="font-size:.85rem">{{ $m->technician ?? '—' }}</td>
                <td><span class="badge rounded-pill" style="background:{{ $statusBg[$m->status] }};color:{{ $statusColor[$m->status] }};font-size:.75rem">{{ $statusLabel[$m->status] }}</span></td>
                <td style="font-size:.85rem">{{ $m->cost ? '$'.number_format($m->cost,2) : '—' }}</td>
                <td>
                    <div class="d-flex gap-1">
                        <a href="{{ route('maintenance.show',$m) }}" class="btn btn-sm" style="background:var(--bg-hover);border:1px solid var(--border);padding:.25rem .5rem"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('maintenance.edit',$m) }}" class="btn btn-sm" style="background:rgba(20,184,166,.15);border:none;color:var(--accent);padding:.25rem .5rem"><i class="bi bi-pencil"></i></a>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center py-5" style="color:var(--text-muted)"><i class="bi bi-tools fs-2 d-block mb-2"></i>No hay registros de mantenimiento.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($records->hasPages())<div class="card-body pt-0">{{ $records->links() }}</div>@endif
</div>
@endsection
