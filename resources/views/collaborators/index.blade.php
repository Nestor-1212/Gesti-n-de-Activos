@extends('layouts.app')
@section('title', 'Colaboradores')
@section('page-title', 'Gestión de Colaboradores')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h5 class="mb-0 fw-bold">Colaboradores</h5>
        <small style="color:var(--text-muted)">{{ $collaborators->total() }} colaboradores registrados</small>
    </div>
    <a href="{{ route('collaborators.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Nuevo Colaborador
    </a>
</div>

<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('collaborators.index') }}" class="row g-2 align-items-end">
            <div class="col-12 col-md-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Nombre, cédula, correo…" value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-6 col-md-3">
                <select name="department" class="form-select">
                    <option value="">Todos los departamentos</option>
                    @foreach($departments as $d)
                        <option value="{{ $d->id }}" {{ request('department')==$d->id?'selected':'' }}>{{ $d->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <select name="status" class="form-select">
                    <option value="">Todos</option>
                    <option value="active" {{ request('status')=='active'?'selected':'' }}>Activos</option>
                    <option value="inactive" {{ request('status')=='inactive'?'selected':'' }}>Inactivos</option>
                </select>
            </div>
            <div class="col-12 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill">Filtrar</button>
                <a href="{{ route('collaborators.index') }}" class="btn" style="background:var(--bg-hover);border:1px solid var(--border)"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr>
                <th width="50"></th><th>Colaborador</th><th>Cédula</th><th>Departamento</th><th>Cargo</th><th>Teléfono</th><th>Estado</th><th>Equipos</th><th width="100">Acciones</th>
            </tr></thead>
            <tbody>
            @forelse($collaborators as $c)
            <tr>
                <td>
                    @if($c->photo)
                        <img src="{{ asset('storage/'.$c->photo) }}" class="rounded-circle" style="width:38px;height:38px;object-fit:cover">
                    @else
                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width:38px;height:38px;background:linear-gradient(135deg,var(--accent),#f97316);font-size:.85rem">
                            {{ strtoupper(substr($c->first_name,0,1).substr($c->last_name,0,1)) }}
                        </div>
                    @endif
                </td>
                <td>
                    <a href="{{ route('collaborators.show', $c) }}" class="fw-600 text-decoration-none" style="color:var(--text)">{{ $c->full_name }}</a>
                    <div style="font-size:.75rem;color:var(--text-muted)">{{ $c->email ?? '—' }}</div>
                </td>
                <td style="font-size:.85rem">{{ $c->cedula }}</td>
                <td style="font-size:.85rem">{{ $c->department?->name ?? '—' }}</td>
                <td style="font-size:.85rem">{{ $c->position ?? '—' }}</td>
                <td style="font-size:.85rem">{{ $c->phone ?? '—' }}</td>
                <td>
                    <span class="badge rounded-pill {{ $c->status==='active'?'badge-active':'badge-retired' }}" style="font-size:.75rem">
                        {{ $c->status === 'active' ? 'Activo' : 'Inactivo' }}
                    </span>
                </td>
                <td>
                    @php $eq = $c->assignments->where('status','active')->count(); @endphp
                    @if($eq > 0)
                        <span class="badge rounded-pill badge-assigned" style="font-size:.75rem">{{ $eq }} equipo{{ $eq>1?'s':'' }}</span>
                    @else
                        <span style="color:var(--text-muted);font-size:.85rem">—</span>
                    @endif
                </td>
                <td>
                    <div class="d-flex gap-1">
                        <a href="{{ route('collaborators.show', $c) }}" class="btn btn-sm" style="background:var(--bg-hover);border:1px solid var(--border);padding:.25rem .5rem"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('collaborators.edit', $c) }}" class="btn btn-sm" style="background:rgba(20,184,166,.15);border:none;color:var(--accent);padding:.25rem .5rem"><i class="bi bi-pencil"></i></a>
                        <form method="POST" action="{{ route('collaborators.destroy', $c) }}" onsubmit="return confirm('¿Eliminar colaborador?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm" style="background:rgba(239,68,68,.15);border:none;color:#ef4444;padding:.25rem .5rem"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="9" class="text-center py-5" style="color:var(--text-muted)">
                <i class="bi bi-people fs-2 d-block mb-2"></i>No hay colaboradores registrados.
            </td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($collaborators->hasPages())
    <div class="card-body pt-0">{{ $collaborators->links() }}</div>
    @endif
</div>
@endsection
