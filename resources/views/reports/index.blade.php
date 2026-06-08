@extends('layouts.app')
@section('title','Reportes')
@section('page-title','Reportes y Exportaciones')
@section('content')
<h5 class="fw-bold mb-4">Centro de Reportes</h5>
<div class="row g-3">
    @php
    $reports = [
        ['title'=>'Inventario General','desc'=>'Listado completo de todos los equipos con su estado y asignación actual.','icon'=>'bi-collection','color'=>'#14b8a6','route'=>'reports.inventory'],
        ['title'=>'Equipos por Colaborador','desc'=>'Muestra todos los equipos asignados a cada colaborador activo.','icon'=>'bi-person-badge','color'=>'#22c55e','route'=>'reports.by-collaborator'],
        ['title'=>'Equipos por Departamento','desc'=>'Distribución de equipos por departamento de la empresa.','icon'=>'bi-building','color'=>'#2dd4bf','route'=>'reports.by-department'],
        ['title'=>'Garantías por Vencer','desc'=>'Equipos con garantía próxima a vencer en los próximos 30/60/90 días.','icon'=>'bi-shield-exclamation','color'=>'#f97316','route'=>'reports.warranty-expiring'],
        ['title'=>'Historial de Asignaciones','desc'=>'Registro completo de todas las asignaciones y devoluciones realizadas.','icon'=>'bi-clock-history','color'=>'#f97316','route'=>'reports.assignment-history'],
    ];
    @endphp
    @foreach($reports as $r)
    <div class="col-12 col-md-6 col-lg-4">
        <div class="card h-100" style="transition:.2s">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width:48px;height:48px;border-radius:12px;background:{{ $r['color'] }}22;display:flex;align-items:center;justify-content:center;font-size:1.4rem;color:{{ $r['color'] }}">
                        <i class="bi {{ $r['icon'] }}"></i>
                    </div>
                    <h6 class="fw-bold mb-0">{{ $r['title'] }}</h6>
                </div>
                <p style="font-size:.85rem;color:var(--text-muted);margin-bottom:1.25rem">{{ $r['desc'] }}</p>
                <div class="d-flex gap-2">
                    <a href="{{ route($r['route']) }}" class="btn btn-sm flex-fill" style="background:{{ $r['color'] }}22;color:{{ $r['color'] }};border:none">
                        <i class="bi bi-eye me-1"></i>Ver
                    </a>
                    <a href="{{ route($r['route'], ['format'=>'pdf']) }}" class="btn btn-sm" style="background:rgba(239,68,68,.15);color:#ef4444;border:none" target="_blank">
                        <i class="bi bi-file-pdf"></i>
                    </a>
                    @if($r['route']==='reports.inventory')
                    <a href="{{ route($r['route'], ['format'=>'excel']) }}" class="btn btn-sm" style="background:rgba(34,197,94,.15);color:#22c55e;border:none">
                        <i class="bi bi-file-excel"></i>
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
