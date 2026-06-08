@extends('layouts.app')
@section('title','Equipos por Departamento')
@section('page-title','Equipos por Departamento')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0 fw-bold">Equipos por Departamento</h5>
    <a href="{{ route('reports.by-department', ['format'=>'pdf']) }}" class="btn btn-sm" style="background:rgba(239,68,68,.15);color:#ef4444;border:none" target="_blank"><i class="bi bi-file-pdf me-1"></i>PDF</a>
</div>
@foreach($departments as $dept)
@php $total = $dept->collaborators->sum(fn($c)=>$c->assignments->count()); @endphp
@if($total > 0)
<div class="card mb-3">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="bi bi-building me-2"></i>{{ $dept->name }}</span>
        <span class="badge rounded-pill badge-assigned">{{ $total }} equipo(s)</span>
    </div>
    <div class="card-body p-0">
    @foreach($dept->collaborators as $c)
    @if($c->assignments->isNotEmpty())
    <div class="px-3 pt-3 pb-1" style="border-bottom:1px solid var(--border)">
        <div class="fw-600 mb-2" style="font-size:.88rem">{{ $c->full_name }} <span style="color:var(--text-muted);font-size:.8rem">({{ $c->position }})</span></div>
        @foreach($c->assignments as $a)
        <div class="d-flex align-items-center gap-3 mb-2 p-2 rounded-3" style="background:var(--bg-hover);font-size:.83rem">
            <i class="bi bi-phone" style="color:var(--accent)"></i>
            <div>{{ $a->equipment->brand }} {{ $a->equipment->model }}</div>
            <div style="color:var(--text-muted)">{{ $a->equipment->serial_number }}</div>
            <div class="ms-auto">{{ $a->assigned_at->format('d/m/Y') }}</div>
        </div>
        @endforeach
    </div>
    @endif
    @endforeach
    </div>
</div>
@endif
@endforeach
@endsection
