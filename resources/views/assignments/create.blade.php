@extends('layouts.app')
@section('title', 'Nueva Asignación')
@section('page-title', 'Nueva Asignación')
@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('assignments.index') }}" class="btn btn-sm" style="background:var(--bg-card);border:1px solid var(--border)"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Asignar Equipo</h5>
</div>
<form method="POST" action="{{ route('assignments.store') }}">
    @csrf
    <div class="row g-3">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header"><i class="bi bi-arrow-left-right me-2"></i>Datos de la Asignación</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Equipo *</label>
                            <select name="equipment_id" class="form-select" required>
                                <option value="">Seleccionar equipo disponible…</option>
                                @foreach($equipment as $eq)
                                    <option value="{{ $eq->id }}" {{ (old('equipment_id',$selectedEquipment?->id)==$eq->id)?'selected':'' }}>
                                        {{ $eq->brand }} {{ $eq->model }} — {{ $eq->serial_number }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Colaborador *</label>
                            <select name="collaborator_id" class="form-select" required>
                                <option value="">Seleccionar colaborador…</option>
                                @foreach($collaborators as $c)
                                    <option value="{{ $c->id }}" {{ old('collaborator_id')==$c->id?'selected':'' }}>
                                        {{ $c->full_name }} — {{ $c->cedula }} ({{ $c->department?->name ?? 'Sin dpto.' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Fecha de Entrega *</label>
                            <input type="date" name="assigned_at" class="form-control" value="{{ old('assigned_at', now()->format('Y-m-d')) }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Fecha Estimada de Devolución</label>
                            <input type="date" name="expected_return" class="form-control" value="{{ old('expected_return') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Motivo de la Asignación</label>
                            <input type="text" name="reason" class="form-control" value="{{ old('reason') }}" placeholder="Trabajo remoto, visita de campo…">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Observaciones</label>
                            <textarea name="observations" class="form-control" rows="3" placeholder="Estado del equipo al momento de la entrega, condiciones especiales…">{{ old('observations') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="card mb-3">
                <div class="card-header"><i class="bi bi-info-circle me-2"></i>Información</div>
                <div class="card-body" style="font-size:.83rem;color:var(--text-muted)">
                    <p class="mb-2">Solo se muestran equipos con estado <strong style="color:#22c55e">Disponible</strong>.</p>
                    <p class="mb-2">Al asignar, el equipo cambia automáticamente a estado <strong style="color:var(--accent)">Asignado</strong>.</p>
                    <p class="mb-0">Se puede generar el acta de entrega en PDF desde el detalle de la asignación.</p>
                </div>
            </div>
        </div>
        <div class="col-12 d-flex gap-2 justify-content-end">
            <a href="{{ route('assignments.index') }}" class="btn" style="background:var(--bg-card);border:1px solid var(--border)">Cancelar</a>
            <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Registrar Asignación</button>
        </div>
    </div>
</form>
@endsection
