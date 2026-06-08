@extends('layouts.app')
@section('title','Inventario General')
@section('page-title','Reporte: Inventario General')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h5 class="mb-0 fw-bold">Inventario General</h5>
        <small style="color:var(--text-muted)">{{ $equipment->count() }} equipos</small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('reports.inventory', array_merge(request()->query(), ['format'=>'pdf'])) }}" class="btn btn-sm" style="background:rgba(239,68,68,.15);color:#ef4444;border:none" target="_blank">
            <i class="bi bi-file-pdf me-1"></i>Exportar PDF
        </a>
        <a href="{{ route('reports.inventory', array_merge(request()->query(), ['format'=>'excel'])) }}" class="btn btn-sm" style="background:rgba(34,197,94,.15);color:#22c55e;border:none">
            <i class="bi bi-file-excel me-1"></i>Exportar Excel
        </a>
    </div>
</div>
<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-6 col-md-3">
                <select name="status" class="form-select">
                    <option value="">Todos los estados</option>
                    @foreach(\App\Models\Equipment::$statuses as $k=>$v)<option value="{{ $k }}" {{ request('status')==$k?'selected':'' }}>{{ $v }}</option>@endforeach
                </select>
            </div>
            <div class="col-6 col-md-3">
                <select name="type" class="form-select">
                    <option value="">Todos los tipos</option>
                    @foreach(\App\Models\Equipment::$types as $k=>$v)<option value="{{ $k }}" {{ request('type')==$k?'selected':'' }}>{{ $v }}</option>@endforeach
                </select>
            </div>
            <div class="col-6 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill">Filtrar</button>
                <a href="{{ route('reports.inventory') }}" class="btn" style="background:var(--bg-hover);border:1px solid var(--border)"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table mb-0" style="font-size:.85rem">
            <thead><tr><th>Marca / Modelo</th><th>Serie</th><th>Tipo</th><th>Estado</th><th>S.O.</th><th>RAM</th><th>Garantía</th><th>Costo</th><th>Asignado A</th></tr></thead>
            <tbody>
            @foreach($equipment as $eq)
            <tr>
                <td>
                    <a href="{{ route('equipment.show',$eq) }}" class="text-decoration-none" style="color:var(--text)">
                        <div class="fw-600">{{ $eq->brand }} {{ $eq->model }}</div>
                        <div style="font-size:.75rem;color:var(--text-muted)">{{ $eq->barcode }}</div>
                    </a>
                </td>
                <td>{{ $eq->serial_number }}</td>
                <td><span class="badge rounded-pill" style="background:rgba(20,184,166,.15);color:var(--accent);font-size:.72rem">{{ $eq->type_label }}</span></td>
                <td>
                    @php $sBadge = ['available'=>'badge-available','assigned'=>'badge-assigned','maintenance'=>'badge-maintenance','damaged'=>'badge-damaged','lost'=>'badge-lost','retired'=>'badge-retired']; @endphp
                    <span class="badge rounded-pill {{ $sBadge[$eq->status]??'' }}" style="font-size:.72rem">{{ $eq->status_label }}</span>
                </td>
                <td>{{ $eq->operating_system ?? '—' }}</td>
                <td>{{ $eq->ram ?? '—' }}</td>
                <td style="color:{{ $eq->warranty_status==='vigente'?'#2dd4bf':($eq->warranty_status==='por-vencer'?'#f97316':'#ef4444') }}">{{ $eq->warranty_expiry?->format('d/m/Y') ?? '—' }}</td>
                <td>{{ $eq->cost ? '$'.number_format($eq->cost,2) : '—' }}</td>
                <td>{{ $eq->activeAssignment?->collaborator?->full_name ?? '—' }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
