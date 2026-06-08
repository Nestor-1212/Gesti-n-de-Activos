@extends('layouts.app')
@section('title','Garantías por Vencer')
@section('page-title','Garantías por Vencer')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h5 class="mb-0 fw-bold">Garantías por Vencer ({{ $days }} días)</h5>
    <div class="d-flex gap-2 align-items-center">
        <form method="GET" class="d-flex gap-2">
            <select name="days" class="form-select form-select-sm">
                <option value="30" {{ $days==30?'selected':'' }}>30 días</option>
                <option value="60" {{ $days==60?'selected':'' }}>60 días</option>
                <option value="90" {{ $days==90?'selected':'' }}>90 días</option>
            </select>
            <button type="submit" class="btn btn-sm btn-primary">Filtrar</button>
        </form>
        <a href="{{ route('reports.warranty-expiring', ['format'=>'pdf','days'=>$days]) }}" class="btn btn-sm" style="background:rgba(239,68,68,.15);color:#ef4444;border:none" target="_blank"><i class="bi bi-file-pdf"></i></a>
    </div>
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Equipo</th><th>Serie</th><th>Proveedor</th><th>Vencimiento</th><th>Días Restantes</th><th>Asignado A</th></tr></thead>
            <tbody>
            @forelse($equipment as $eq)
            @php $dias = now()->diffInDays($eq->warranty_expiry); @endphp
            <tr>
                <td><a href="{{ route('equipment.show',$eq) }}" class="text-decoration-none" style="color:var(--text)">{{ $eq->brand }} {{ $eq->model }}</a></td>
                <td style="font-size:.85rem">{{ $eq->serial_number }}</td>
                <td style="font-size:.85rem">{{ $eq->supplier ?? '—' }}</td>
                <td style="color:{{ $dias<=7?'#ef4444':($dias<=30?'#f97316':'#22c55e') }};font-weight:600">{{ $eq->warranty_expiry->format('d/m/Y') }}</td>
                <td>
                    <span class="badge rounded-pill" style="background:{{ $dias<=7?'rgba(239,68,68,.15)':($dias<=30?'rgba(249,115,22,.15)':'rgba(34,197,94,.15)') }};color:{{ $dias<=7?'#ef4444':($dias<=30?'#f97316':'#22c55e') }}">{{ $dias }} días</span>
                </td>
                <td style="font-size:.85rem">{{ $eq->activeAssignment?->collaborator?->full_name ?? '—' }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center py-5" style="color:var(--text-muted)"><i class="bi bi-shield-check fs-2 d-block mb-2"></i>No hay garantías por vencer en los próximos {{ $days }} días.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
