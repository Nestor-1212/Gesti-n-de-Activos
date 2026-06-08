@extends('layouts.app')
@section('title','Equipos por Colaborador')
@section('page-title','Equipos por Colaborador')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0 fw-bold">Equipos por Colaborador</h5>
    <a href="{{ route('reports.by-collaborator', ['format'=>'pdf']) }}" class="btn btn-sm" style="background:rgba(239,68,68,.15);color:#ef4444;border:none" target="_blank"><i class="bi bi-file-pdf me-1"></i>PDF</a>
</div>
@foreach($collaborators as $c)
@if($c->assignments->isNotEmpty())
<div class="card mb-3">
    <div class="card-header">
        <div class="d-flex align-items-center gap-2">
            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width:34px;height:34px;background:linear-gradient(135deg,var(--accent),#f97316);font-size:.85rem;flex-shrink:0">{{ strtoupper(substr($c->first_name,0,1).substr($c->last_name,0,1)) }}</div>
            <div><div class="fw-bold">{{ $c->full_name }}</div><div style="font-size:.75rem;color:var(--text-muted)">{{ $c->position }} — {{ $c->department?->name }}</div></div>
            <span class="ms-auto badge rounded-pill badge-assigned">{{ $c->assignments->count() }} equipo(s)</span>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table mb-0" style="font-size:.85rem">
            <thead><tr><th>Equipo</th><th>Serie</th><th>Tipo</th><th>Entrega</th><th>Devolución Est.</th></tr></thead>
            <tbody>
            @foreach($c->assignments as $a)
            <tr>
                <td><a href="{{ route('equipment.show',$a->equipment) }}" class="text-decoration-none" style="color:var(--text)">{{ $a->equipment->brand }} {{ $a->equipment->model }}</a></td>
                <td>{{ $a->equipment->serial_number }}</td>
                <td>{{ $a->equipment->type_label }}</td>
                <td>{{ $a->assigned_at->format('d/m/Y') }}</td>
                <td>{{ $a->expected_return?->format('d/m/Y') ?? '—' }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endforeach
@endsection
