@extends('layouts.app')
@section('title','Editar Mantenimiento')
@section('page-title','Editar Mantenimiento')
@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('maintenance.show', $maintenance) }}" class="btn btn-sm" style="background:var(--bg-card);border:1px solid var(--border)"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Editar Mantenimiento #{{ $maintenance->id }}</h5>
</div>
<form method="POST" action="{{ route('maintenance.update', $maintenance) }}">
    @csrf @method('PUT')
    <div class="card" style="max-width:700px">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12"><label class="form-label">Equipo</label><input type="text" class="form-control" value="{{ $maintenance->equipment->brand }} {{ $maintenance->equipment->model }}" disabled></div>
                <div class="col-6">
                    <label class="form-label">Tipo</label>
                    <select name="type" class="form-select" required>
                        <option value="corrective" {{ old('type',$maintenance->type)==='corrective'?'selected':'' }}>Correctivo</option>
                        <option value="preventive" {{ old('type',$maintenance->type)==='preventive'?'selected':'' }}>Preventivo</option>
                        <option value="upgrade" {{ old('type',$maintenance->type)==='upgrade'?'selected':'' }}>Actualización</option>
                    </select>
                </div>
                <div class="col-6">
                    <label class="form-label">Estado</label>
                    <select name="status" class="form-select" required>
                        <option value="pending" {{ old('status',$maintenance->status)==='pending'?'selected':'' }}>Pendiente</option>
                        <option value="in_progress" {{ old('status',$maintenance->status)==='in_progress'?'selected':'' }}>En Proceso</option>
                        <option value="completed" {{ old('status',$maintenance->status)==='completed'?'selected':'' }}>Completado</option>
                    </select>
                </div>
                <div class="col-12"><label class="form-label">Descripción *</label><textarea name="description" class="form-control" rows="3" required>{{ old('description',$maintenance->description) }}</textarea></div>
                <div class="col-6"><label class="form-label">Fecha</label><input type="date" name="maintenance_date" class="form-control" value="{{ old('maintenance_date',$maintenance->maintenance_date->format('Y-m-d')) }}" required></div>
                <div class="col-6"><label class="form-label">Fecha Completado</label><input type="date" name="completed_date" class="form-control" value="{{ old('completed_date',$maintenance->completed_date?->format('Y-m-d')) }}"></div>
                <div class="col-6"><label class="form-label">Técnico</label><input type="text" name="technician" class="form-control" value="{{ old('technician',$maintenance->technician) }}"></div>
                <div class="col-6"><label class="form-label">Costo ($)</label><input type="number" name="cost" class="form-control" value="{{ old('cost',$maintenance->cost) }}" step="0.01" min="0"></div>
                <div class="col-12"><label class="form-label">Notas</label><textarea name="notes" class="form-control" rows="2">{{ old('notes',$maintenance->notes) }}</textarea></div>
            </div>
        </div>
    </div>
    <div class="mt-3 d-flex gap-2">
        <a href="{{ route('maintenance.show', $maintenance) }}" class="btn" style="background:var(--bg-card);border:1px solid var(--border)">Cancelar</a>
        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Guardar Cambios</button>
    </div>
</form>
@endsection
