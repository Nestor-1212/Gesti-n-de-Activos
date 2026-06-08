@extends('layouts.app')
@section('title','Editar Usuario')
@section('page-title','Editar Usuario')
@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('users.index') }}" class="btn btn-sm" style="background:var(--bg-card);border:1px solid var(--border)"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Editar: {{ $user->name }}</h5>
</div>
<form method="POST" action="{{ route('users.update', $user) }}">
    @csrf @method('PUT')
    <div class="card" style="max-width:550px">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12"><label class="form-label">Nombre *</label><input type="text" name="name" class="form-control" value="{{ old('name',$user->name) }}" required></div>
                <div class="col-12"><label class="form-label">Correo *</label><input type="email" name="email" class="form-control" value="{{ old('email',$user->email) }}" required></div>
                <div class="col-12"><label class="form-label">Rol *</label>
                    <select name="role" class="form-select" required>
                        @foreach($roles as $r)<option value="{{ $r->name }}" {{ $user->hasRole($r->name)?'selected':'' }}>{{ $r->name }}</option>@endforeach
                    </select>
                </div>
                <div class="col-12"><label class="form-label">Nueva Contraseña <span style="color:var(--text-muted);font-size:.8rem">(dejar vacío para no cambiar)</span></label><input type="password" name="password" class="form-control"></div>
                <div class="col-12"><label class="form-label">Confirmar Contraseña</label><input type="password" name="password_confirmation" class="form-control"></div>
                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="active" value="1" id="active" {{ $user->active?'checked':'' }}>
                        <label class="form-check-label" for="active">Usuario activo</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-3 d-flex gap-2">
        <a href="{{ route('users.index') }}" class="btn" style="background:var(--bg-card);border:1px solid var(--border)">Cancelar</a>
        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Guardar Cambios</button>
    </div>
</form>
@endsection
