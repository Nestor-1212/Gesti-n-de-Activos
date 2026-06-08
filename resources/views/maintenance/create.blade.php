@extends('layouts.app')
@section('title','Nuevo Mantenimiento')
@section('page-title','Registrar Mantenimiento')
@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('maintenance.index') }}" class="btn btn-sm" style="background:var(--bg-card);border:1px solid var(--border)"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Nuevo Mantenimiento</h5>
</div>
<form method="POST" action="{{ route('maintenance.store') }}">
    @csrf
    <div class="card" style="max-width:700px">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Equipo *</label>
                    <select name="equipment_id" class="form-select" required>
                        <option value="">Seleccionar equipo…</option>
                        @foreach($equipment as $eq)
                            <option value="{{ $eq->id }}" {{ (old('equipment_id',$selected?->id)==$eq->id)?'selected':'' }}>{{ $eq->brand }} {{ $eq->model }} — {{ $eq->serial_number }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6">
                    <label class="form-label">Tipo *</label>
                    <select name="type" class="form-select" required>
                        <option value="corrective" {{ old('type')==='corrective'?'selected':'' }}>Correctivo</option>
                        <option value="preventive" {{ old('type')==='preventive'?'selected':'' }}>Preventivo</option>
                        <option value="upgrade" {{ old('type')==='upgrade'?'selected':'' }}>Actualización</option>
                    </select>
                </div>
                <div class="col-6">
                    <label class="form-label">Estado *</label>
                    <select name="status" class="form-select" required>
                        <option value="pending" {{ old('status','pending')==='pending'?'selected':'' }}>Pendiente</option>
                        <option value="in_progress" {{ old('status')==='in_progress'?'selected':'' }}>En Proceso</option>
                        <option value="completed" {{ old('status')==='completed'?'selected':'' }}>Completado</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Descripción *</label>
                    <textarea name="description" class="form-control" rows="3" required>{{ old('description') }}</textarea>
                </div>
                <div class="col-6">
                    <label class="form-label">Fecha de Mantenimiento *</label>
                    <input type="date" name="maintenance_date" class="form-control" value="{{ old('maintenance_date', now()->format('Y-m-d')) }}" required>
                </div>
                <div class="col-6">
                    <label class="form-label">Técnico</label>
                    <input type="text" name="technician" class="form-control" value="{{ old('technician') }}" placeholder="Nombre del técnico">
                </div>
                <div class="col-6">
                    <label class="form-label">Costo ($)</label>
                    <input type="number" name="cost" class="form-control" value="{{ old('cost') }}" step="0.01" min="0">
                </div>
                <div class="col-12">
                    <label class="form-label">Notas</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-3 d-flex gap-2">
        <a href="{{ route('maintenance.index') }}" class="btn" style="background:var(--bg-card);border:1px solid var(--border)">Cancelar</a>
        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Registrar</button>
    </div>
</form>
@endsection
