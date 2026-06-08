@extends('layouts.app')
@section('title', 'Asignaciones')
@section('page-title', 'Control de Asignaciones')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h5 class="mb-0 fw-bold">Asignaciones</h5>
        <small style="color:var(--text-muted)">{{ $assignments->total() }} registros</small>
    </div>
    <a href="{{ route('assignments.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Nueva Asignación</a>
</div>
<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-12 col-md-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Colaborador, equipo, serie…" value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-6 col-md-3">
                <select name="status" class="form-select">
                    <option value="">Todos los estados</option>
                    <option value="active" {{ request('status')==='active'?'selected':'' }}>Activos</option>
                    <option value="returned" {{ request('status')==='returned'?'selected':'' }}>Devueltos</option>
                    <option value="overdue" {{ request('status')==='overdue'?'selected':'' }}>Vencidos</option>
                </select>
            </div>
            <div class="col-6 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill">Filtrar</button>
                <a href="{{ route('assignments.index') }}" class="btn" style="background:var(--bg-hover);border:1px solid var(--border)"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Equipo</th><th>Colaborador</th><th>Departamento</th><th>Entrega</th><th>Devolución Est.</th><th>Estado</th><th>Asignado por</th><th width="100">Acciones</th></tr></thead>
            <tbody>
            @forelse($assignments as $a)
            <tr>
                <td>
                    <div class="fw-600" style="font-size:.9rem">{{ $a->equipment->brand }} {{ $a->equipment->model }}</div>
                    <div style="font-size:.75rem;color:var(--text-muted)">{{ $a->equipment->serial_number }}</div>
                </td>
                <td style="font-size:.85rem">{{ $a->collaborator->full_name }}</td>
                <td style="font-size:.85rem">{{ $a->collaborator->department?->name ?? '—' }}</td>
                <td style="font-size:.85rem">{{ $a->assigned_at->format('d/m/Y') }}</td>
                <td style="font-size:.85rem;color:{{ $a->expected_return && $a->expected_return->isPast() && $a->status==='active' ? '#ef4444' : 'var(--text)' }}">
                    {{ $a->expected_return?->format('d/m/Y') ?? '—' }}
                </td>
                <td>
                    <span class="badge rounded-pill badge-{{ $a->status }}" style="font-size:.75rem">
                        {{ $a->status === 'active' ? 'Activo' : ($a->status === 'returned' ? 'Devuelto' : 'Vencido') }}
                    </span>
                </td>
                <td style="font-size:.85rem">{{ $a->assignedBy->name }}</td>
                <td>
                    <div class="d-flex gap-1">
                        <a href="{{ route('assignments.show', $a) }}" class="btn btn-sm" style="background:var(--bg-hover);border:1px solid var(--border);padding:.25rem .5rem"><i class="bi bi-eye"></i></a>
                        @if($a->status === 'active')
                        <form method="POST" action="{{ route('assignments.return', $a) }}" onsubmit="return confirm('¿Registrar devolución?')">
                            @csrf
                            <button type="submit" class="btn btn-sm" style="background:rgba(34,197,94,.15);border:none;color:#22c55e;padding:.25rem .5rem" title="Devolver">
                                <i class="bi bi-arrow-return-left"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center py-5" style="color:var(--text-muted)">
                <i class="bi bi-arrow-left-right fs-2 d-block mb-2"></i>No hay asignaciones registradas.
            </td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($assignments->hasPages())<div class="card-body pt-0">{{ $assignments->links() }}</div>@endif
</div>
@endsection
