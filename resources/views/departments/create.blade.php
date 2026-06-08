@extends('layouts.app')
@section('title','Nuevo Departamento')
@section('page-title','Nuevo Departamento')
@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('departments.index') }}" class="btn btn-sm" style="background:var(--bg-card);border:1px solid var(--border)"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Crear Departamento</h5>
</div>
<form method="POST" action="{{ route('departments.store') }}">
    @csrf
    <div class="card" style="max-width:600px">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-8"><label class="form-label">Nombre *</label><input type="text" name="name" class="form-control" value="{{ old('name') }}" required></div>
                <div class="col-4"><label class="form-label">Código</label><input type="text" name="code" class="form-control" value="{{ old('code') }}" placeholder="TI"></div>
                <div class="col-12"><label class="form-label">Descripción</label><textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea></div>
                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="active" value="1" id="active" {{ old('active',1)?'checked':'' }}>
                        <label class="form-check-label" for="active">Departamento activo</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-3 d-flex gap-2">
        <a href="{{ route('departments.index') }}" class="btn" style="background:var(--bg-card);border:1px solid var(--border)">Cancelar</a>
        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Guardar</button>
    </div>
</form>
@endsection
