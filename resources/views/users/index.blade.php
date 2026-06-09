@extends('layouts.app')
@section('title','Usuarios')
@section('page-title','Gestión de Usuarios')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0 fw-bold">Usuarios del Sistema</h5>
    <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Nuevo Usuario</a>
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Usuario</th><th>Correo</th><th>Rol</th><th>Estado</th><th>Creado</th><th width="120">Acciones</th></tr></thead>
            <tbody>
            @forelse($users as $u)
            <tr>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width:34px;height:34px;background:linear-gradient(135deg,var(--accent),#f97316);font-size:.85rem;flex-shrink:0">{{ strtoupper(substr($u->name,0,1)) }}</div>
                        <span class="fw-600">{{ $u->name }}</span>
                        @if($u->id === auth()->id())<span class="badge rounded-pill ms-1" style="background:rgba(34,197,94,.15);color:#22c55e;font-size:.7rem">Tú</span>@endif
                    </div>
                </td>
                <td style="font-size:.85rem">{{ $u->email }}</td>
                <td>
                    @foreach($u->roles as $role)
                    <span class="badge rounded-pill" style="background:rgba(249,115,22,.15);color:#f97316;font-size:.75rem">{{ $role->name }}</span>
                    @endforeach
                </td>
                <td><span class="badge rounded-pill {{ $u->active?'badge-active':'badge-retired' }}" style="font-size:.75rem">{{ $u->active?'Activo':'Inactivo' }}</span></td>
                <td style="font-size:.85rem">{{ $u->created_at->format('d/m/Y') }}</td>
                <td>
                    <div class="d-flex gap-1">
                        <a href="{{ route('users.edit',$u) }}" class="btn btn-sm" style="background:rgba(20,184,166,.15);border:none;color:var(--accent);padding:.25rem .5rem"><i class="bi bi-pencil"></i></a>
                        @if($u->id !== auth()->id())
                        <form method="POST" action="{{ route('users.destroy',$u) }}">
                            @csrf @method('DELETE')
                            <button type="button" class="btn btn-sm" style="background:rgba(239,68,68,.15);border:none;color:#ef4444;padding:.25rem .5rem" title="Eliminar"
                                onclick="confirmDelete(this.closest('form'), '¿Eliminar usuario?', 'Se eliminará al usuario {{ addslashes($u->name) }}. Esta acción no se puede deshacer.')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center py-5" style="color:var(--text-muted)">Sin usuarios registrados.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())<div class="card-body pt-0">{{ $users->links() }}</div>@endif
</div>
@endsection
