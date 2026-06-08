@extends('layouts.app')
@section('title', 'Equipos')
@section('page-title', 'Gestión de Equipos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h5 class="mb-0 fw-bold">Equipos Registrados</h5>
        <small style="color:var(--text-muted)">{{ $equipment->total() }} equipos en total</small>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('scanner.index') }}" class="btn btn-sm" style="background:var(--bg-card);border:1px solid var(--border)">
            <i class="bi bi-qr-code-scan me-1"></i>Escanear
        </a>
        <a href="{{ route('equipment.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Nuevo Equipo
        </a>
    </div>
</div>

{{-- Filters --}}
<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('equipment.index') }}" class="row g-2 align-items-end">
            <div class="col-12 col-md-5">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Buscar por marca, modelo, serie, IMEI…" value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-6 col-md-3">
                <select name="status" class="form-select">
                    <option value="">Todos los estados</option>
                    @foreach(\App\Models\Equipment::$statuses as $k=>$v)
                        <option value="{{ $k }}" {{ request('status')==$k?'selected':'' }}>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <select name="type" class="form-select">
                    <option value="">Todos los tipos</option>
                    @foreach(\App\Models\Equipment::$types as $k=>$v)
                        <option value="{{ $k }}" {{ request('type')==$k?'selected':'' }}>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill">Filtrar</button>
                <a href="{{ route('equipment.index') }}" class="btn" style="background:var(--bg-hover);border:1px solid var(--border)">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th width="50"></th>
                    <th>Equipo</th>
                    <th>Serie / IMEI</th>
                    <th>Tipo</th>
                    <th>Estado</th>
                    <th>Asignado A</th>
                    <th>Garantía</th>
                    <th width="120">Acciones</th>
                </tr>
            </thead>
            <tbody>
            @forelse($equipment as $eq)
            <tr>
                <td>
                    @if($eq->mainPhoto)
                        <img src="{{ asset('storage/'.$eq->mainPhoto->path) }}" class="rounded" style="width:40px;height:40px;object-fit:cover">
                    @else
                        <div class="rounded d-flex align-items-center justify-content-center" style="width:40px;height:40px;background:var(--bg-hover)">
                            <i class="bi bi-phone" style="color:var(--text-muted)"></i>
                        </div>
                    @endif
                </td>
                <td>
                    <a href="{{ route('equipment.show', $eq) }}" class="fw-600 text-decoration-none" style="color:var(--text)">
                        {{ $eq->brand }} {{ $eq->model }}
                    </a>
                    <div style="font-size:.75rem;color:var(--text-muted)">{{ $eq->barcode }}</div>
                </td>
                <td style="font-size:.85rem">
                    {{ $eq->serial_number }}<br>
                    @if($eq->imei1)<span style="color:var(--text-muted);font-size:.75rem">{{ $eq->imei1 }}</span>@endif
                </td>
                <td>
                    <span class="badge rounded-pill" style="background:rgba(20,184,166,.15);color:#2dd4bf;font-size:.75rem">
                        {{ $eq->type_label }}
                    </span>
                </td>
                <td>
                    @php
                    $sBadge = ['available'=>'badge-available','assigned'=>'badge-assigned','maintenance'=>'badge-maintenance','damaged'=>'badge-damaged','lost'=>'badge-lost','retired'=>'badge-retired'];
                    @endphp
                    <span class="badge rounded-pill {{ $sBadge[$eq->status] ?? '' }}" style="font-size:.75rem">
                        {{ $eq->status_label }}
                    </span>
                </td>
                <td style="font-size:.85rem">
                    {{ $eq->activeAssignment?->collaborator?->full_name ?? '—' }}
                </td>
                <td style="font-size:.82rem">
                    @if($eq->warranty_expiry)
                        @php $ws = $eq->warranty_status; @endphp
                        <span style="color:{{ $ws==='vigente'?'#2dd4bf':($ws==='por-vencer'?'#f97316':'#ef4444') }}">
                            {{ $eq->warranty_expiry->format('d/m/Y') }}
                        </span>
                    @else —
                    @endif
                </td>
                <td>
                    <div class="d-flex gap-1">
                        <a href="{{ route('equipment.show', $eq) }}" class="btn btn-sm" title="Ver" style="background:var(--bg-hover);border:1px solid var(--border);padding:.25rem .5rem">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('equipment.edit', $eq) }}" class="btn btn-sm" title="Editar" style="background:rgba(20,184,166,.15);border:none;color:var(--accent);padding:.25rem .5rem">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="{{ route('equipment.destroy', $eq) }}" onsubmit="return confirm('¿Eliminar este equipo?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm" style="background:rgba(239,68,68,.15);border:none;color:#ef4444;padding:.25rem .5rem" title="Eliminar">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center py-5" style="color:var(--text-muted)">
                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                    No hay equipos registrados.
                    <a href="{{ route('equipment.create') }}" class="d-block mt-2 text-decoration-none" style="color:var(--accent)">Registrar primer equipo</a>
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($equipment->hasPages())
    <div class="card-body pt-0">
        {{ $equipment->links() }}
    </div>
    @endif
</div>
@endsection
