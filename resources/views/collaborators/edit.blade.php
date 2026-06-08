@extends('layouts.app')
@section('title', 'Editar Colaborador')
@section('page-title', 'Editar Colaborador')
@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('collaborators.show', $collaborator) }}" class="btn btn-sm" style="background:var(--bg-card);border:1px solid var(--border)"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Editar: {{ $collaborator->full_name }}</h5>
</div>
<form method="POST" action="{{ route('collaborators.update', $collaborator) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="row g-3">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header"><i class="bi bi-person me-2"></i>Datos del Colaborador</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6"><label class="form-label">Nombre *</label><input type="text" name="first_name" class="form-control" value="{{ old('first_name',$collaborator->first_name) }}" required></div>
                        <div class="col-6"><label class="form-label">Apellido *</label><input type="text" name="last_name" class="form-control" value="{{ old('last_name',$collaborator->last_name) }}" required></div>
                        <div class="col-6"><label class="form-label">Cédula *</label><input type="text" name="cedula" class="form-control" value="{{ old('cedula',$collaborator->cedula) }}" required></div>
                        <div class="col-6"><label class="form-label">Teléfono</label><input type="text" name="phone" class="form-control" value="{{ old('phone',$collaborator->phone) }}"></div>
                        <div class="col-12"><label class="form-label">Correo</label><input type="email" name="email" class="form-control" value="{{ old('email',$collaborator->email) }}"></div>
                        <div class="col-6"><label class="form-label">Departamento</label>
                            <select name="department_id" class="form-select">
                                <option value="">Sin departamento</option>
                                @foreach($departments as $d)<option value="{{ $d->id }}" {{ old('department_id',$collaborator->department_id)==$d->id?'selected':'' }}>{{ $d->name }}</option>@endforeach
                            </select>
                        </div>
                        <div class="col-6"><label class="form-label">Cargo</label><input type="text" name="position" class="form-control" value="{{ old('position',$collaborator->position) }}"></div>
                        <div class="col-6"><label class="form-label">Supervisor</label><input type="text" name="supervisor" class="form-control" value="{{ old('supervisor',$collaborator->supervisor) }}"></div>
                        <div class="col-6"><label class="form-label">Estado</label>
                            <select name="status" class="form-select">
                                <option value="active" {{ old('status',$collaborator->status)==='active'?'selected':'' }}>Activo</option>
                                <option value="inactive" {{ old('status',$collaborator->status)==='inactive'?'selected':'' }}>Inactivo</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="card">
                <div class="card-header"><i class="bi bi-camera me-2"></i>Foto</div>
                <div class="card-body text-center">
                    @if($collaborator->photo)
                        <img src="{{ asset('storage/'.$collaborator->photo) }}" class="rounded-circle mb-3" style="width:100px;height:100px;object-fit:cover">
                    @else
                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold mx-auto mb-3" style="width:100px;height:100px;background:linear-gradient(135deg,var(--accent),#f97316);font-size:2rem">
                            {{ strtoupper(substr($collaborator->first_name,0,1).substr($collaborator->last_name,0,1)) }}
                        </div>
                    @endif
                    <input type="file" name="photo" accept="image/*" class="form-control">
                </div>
            </div>
        </div>
        <div class="col-12 d-flex gap-2 justify-content-end">
            <a href="{{ route('collaborators.show',$collaborator) }}" class="btn" style="background:var(--bg-card);border:1px solid var(--border)">Cancelar</a>
            <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Guardar Cambios</button>
        </div>
    </div>
</form>
@endsection
