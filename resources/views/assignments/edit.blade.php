@extends('layouts.app')
@section('title', 'Editar Asignación')
@section('page-title', 'Editar Asignación')
@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('assignments.show', $assignment) }}" class="btn btn-sm" style="background:var(--bg-card);border:1px solid var(--border)"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Editar Asignación #{{ $assignment->id }}</h5>
</div>
<form method="POST" action="{{ route('assignments.update', $assignment) }}">
    @csrf @method('PUT')
    <div class="card">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <label class="form-label">Equipo</label>
                    <input type="text" class="form-control" value="{{ $assignment->equipment->brand }} {{ $assignment->equipment->model }}" disabled>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label">Colaborador</label>
                    <input type="text" class="form-control" value="{{ $assignment->collaborator->full_name }}" disabled>
                </div>
                <div class="col-6">
                    <label class="form-label">Fecha Est. Devolución</label>
                    <input type="date" name="expected_return" class="form-control" value="{{ old('expected_return', $assignment->expected_return?->format('Y-m-d')) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Observaciones</label>
                    <textarea name="observations" class="form-control" rows="3">{{ old('observations', $assignment->observations) }}</textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-3 d-flex gap-2 justify-content-end">
        <a href="{{ route('assignments.show', $assignment) }}" class="btn" style="background:var(--bg-card);border:1px solid var(--border)">Cancelar</a>
        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Guardar</button>
    </div>
</form>
@endsection
