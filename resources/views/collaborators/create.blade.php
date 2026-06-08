@extends('layouts.app')
@section('title', 'Nuevo Colaborador')
@section('page-title', 'Registrar Colaborador')

@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('collaborators.index') }}" class="btn btn-sm" style="background:var(--bg-card);border:1px solid var(--border)"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Registrar Colaborador</h5>
</div>

<form method="POST" action="{{ route('collaborators.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="row g-3">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header"><i class="bi bi-person me-2"></i>Datos Personales</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label">Nombre *</label>
                            <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Apellido *</label>
                            <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Cédula *</label>
                            <input type="text" name="cedula" class="form-control" value="{{ old('cedula') }}" placeholder="8-888-0001" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="6000-0000">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Correo Electrónico</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="colaborador@empresa.com">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Departamento</label>
                            <select name="department_id" class="form-select">
                                <option value="">Sin departamento</option>
                                @foreach($departments as $d)
                                    <option value="{{ $d->id }}" {{ old('department_id')==$d->id?'selected':'' }}>{{ $d->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Cargo</label>
                            <input type="text" name="position" class="form-control" value="{{ old('position') }}" placeholder="Analista, Asistente…">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Supervisor</label>
                            <input type="text" name="supervisor" class="form-control" value="{{ old('supervisor') }}" placeholder="Nombre del supervisor">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Estado *</label>
                            <select name="status" class="form-select" required>
                                <option value="active" {{ old('status','active')==='active'?'selected':'' }}>Activo</option>
                                <option value="inactive" {{ old('status')==='inactive'?'selected':'' }}>Inactivo</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="card">
                <div class="card-header"><i class="bi bi-camera me-2"></i>Foto del Colaborador</div>
                <div class="card-body text-center">
                    <div id="photo-preview" style="width:120px;height:120px;border-radius:50%;background:var(--bg-hover);margin:0 auto 1rem;overflow:hidden;border:3px solid var(--border);cursor:pointer" onclick="document.getElementById('photo').click()">
                        <div class="h-100 d-flex align-items-center justify-content-center" style="color:var(--text-muted)">
                            <i class="bi bi-person fs-1"></i>
                        </div>
                    </div>
                    <input type="file" id="photo" name="photo" accept="image/*" class="d-none" onchange="previewPhoto(this)">
                    <button type="button" class="btn btn-sm w-100" style="background:var(--bg-hover);border:1px solid var(--border)" onclick="document.getElementById('photo').click()">
                        <i class="bi bi-upload me-1"></i>Seleccionar Foto
                    </button>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('collaborators.index') }}" class="btn" style="background:var(--bg-card);border:1px solid var(--border)">Cancelar</a>
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Registrar Colaborador</button>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('photo-preview').innerHTML = `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection
