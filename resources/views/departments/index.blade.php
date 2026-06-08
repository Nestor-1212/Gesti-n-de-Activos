@extends('layouts.app')
@section('title','Departamentos')
@section('page-title','Departamentos')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0 fw-bold">Departamentos</h5>
    <a href="{{ route('departments.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Nuevo Departamento</a>
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Nombre</th><th>Código</th><th>Descripción</th><th>Colaboradores</th><th>Estado</th><th width="120">Acciones</th></tr></thead>
            <tbody>
            @forelse($departments as $d)
            <tr>
                <td class="fw-600">{{ $d->name }}</td>
                <td><span class="badge rounded-pill" style="background:rgba(20,184,166,.15);color:var(--accent);font-size:.75rem">{{ $d->code ?? '—' }}</span></td>
                <td style="font-size:.85rem;color:var(--text-muted)">{{ Str::limit($d->description,60) ?? '—' }}</td>
                <td><span class="badge rounded-pill badge-assigned" style="font-size:.75rem">{{ $d->collaborators_count }}</span></td>
                <td><span class="badge rounded-pill {{ $d->active?'badge-active':'badge-retired' }}" style="font-size:.75rem">{{ $d->active?'Activo':'Inactivo' }}</span></td>
                <td>
                    <div class="d-flex gap-1">
                        <a href="{{ route('departments.edit',$d) }}" class="btn btn-sm" style="background:rgba(20,184,166,.15);border:none;color:var(--accent);padding:.25rem .5rem"><i class="bi bi-pencil"></i></a>
                        <form method="POST" action="{{ route('departments.destroy',$d) }}" onsubmit="return confirm('¿Eliminar departamento?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm" style="background:rgba(239,68,68,.15);border:none;color:#ef4444;padding:.25rem .5rem"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center py-5" style="color:var(--text-muted)"><i class="bi bi-building fs-2 d-block mb-2"></i>No hay departamentos registrados.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($departments->hasPages())<div class="card-body pt-0">{{ $departments->links() }}</div>@endif
</div>
@endsection
