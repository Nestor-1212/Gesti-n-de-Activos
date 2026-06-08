@extends('layouts.app')
@section('title','Mantenimiento #'.$maintenance->id)
@section('page-title','Detalle de Mantenimiento')
@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('maintenance.index') }}" class="btn btn-sm" style="background:var(--bg-card);border:1px solid var(--border)"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Mantenimiento #{{ $maintenance->id }}</h5>
    <div class="ms-auto">
        <a href="{{ route('maintenance.edit', $maintenance) }}" class="btn btn-sm" style="background:rgba(20,184,166,.15);border:none;color:var(--accent)"><i class="bi bi-pencil me-1"></i>Editar</a>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="row g-3" style="font-size:.88rem">
            <div class="col-6 col-md-3"><div style="color:var(--text-muted);font-size:.75rem">Equipo</div><div class="fw-600">{{ $maintenance->equipment->brand }} {{ $maintenance->equipment->model }}</div></div>
            <div class="col-6 col-md-3"><div style="color:var(--text-muted);font-size:.75rem">Tipo</div><div>{{ ['preventive'=>'Preventivo','corrective'=>'Correctivo','upgrade'=>'Actualización'][$maintenance->type] }}</div></div>
            <div class="col-6 col-md-3"><div style="color:var(--text-muted);font-size:.75rem">Estado</div><div>{{ ['pending'=>'Pendiente','in_progress'=>'En Proceso','completed'=>'Completado'][$maintenance->status] }}</div></div>
            <div class="col-6 col-md-3"><div style="color:var(--text-muted);font-size:.75rem">Costo</div><div>{{ $maintenance->cost ? '$'.number_format($maintenance->cost,2) : '—' }}</div></div>
            <div class="col-6 col-md-3"><div style="color:var(--text-muted);font-size:.75rem">Fecha</div><div>{{ $maintenance->maintenance_date->format('d/m/Y') }}</div></div>
            <div class="col-6 col-md-3"><div style="color:var(--text-muted);font-size:.75rem">Completado</div><div>{{ $maintenance->completed_date?->format('d/m/Y') ?? '—' }}</div></div>
            <div class="col-6 col-md-3"><div style="color:var(--text-muted);font-size:.75rem">Técnico</div><div>{{ $maintenance->technician ?? '—' }}</div></div>
            <div class="col-6 col-md-3"><div style="color:var(--text-muted);font-size:.75rem">Registrado por</div><div>{{ $maintenance->user?->name ?? '—' }}</div></div>
            <div class="col-12"><div style="color:var(--text-muted);font-size:.75rem">Descripción</div><div class="p-3 rounded-3 mt-1" style="background:var(--bg-hover)">{{ $maintenance->description }}</div></div>
            @if($maintenance->notes)<div class="col-12"><div style="color:var(--text-muted);font-size:.75rem">Notas</div><div class="p-3 rounded-3 mt-1" style="background:var(--bg-hover)">{{ $maintenance->notes }}</div></div>@endif
        </div>
    </div>
</div>
@endsection
