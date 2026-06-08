@extends('layouts.app')
@section('title', $department->name)
@section('page-title', 'Departamento')
@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('departments.index') }}" class="btn btn-sm" style="background:var(--bg-card);border:1px solid var(--border)"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">{{ $department->name }}</h5>
    <a href="{{ route('departments.edit', $department) }}" class="btn btn-sm ms-auto" style="background:rgba(20,184,166,.15);border:none;color:var(--accent)"><i class="bi bi-pencil me-1"></i>Editar</a>
</div>
<div class="card">
    <div class="card-body">
        <p>{{ $department->description ?? 'Sin descripción.' }}</p>
    </div>
    <div class="card-header"><i class="bi bi-people me-2"></i>Colaboradores ({{ $department->collaborators->count() }})</div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Nombre</th><th>Cargo</th><th>Estado</th></tr></thead>
            <tbody>
            @forelse($department->collaborators as $c)
            <tr>
                <td><a href="{{ route('collaborators.show',$c) }}" class="text-decoration-none" style="color:var(--text)">{{ $c->full_name }}</a></td>
                <td>{{ $c->position ?? '—' }}</td>
                <td><span class="badge rounded-pill {{ $c->status==='active'?'badge-active':'badge-retired' }}" style="font-size:.75rem">{{ $c->status==='active'?'Activo':'Inactivo' }}</span></td>
            </tr>
            @empty
            <tr><td colspan="3" class="text-center py-4" style="color:var(--text-muted)">Sin colaboradores en este departamento.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
