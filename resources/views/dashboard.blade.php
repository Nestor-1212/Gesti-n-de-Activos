@extends('layouts.app')
@section('title', 'Dashboard — Activos Tecnológicos')
@section('page-title', 'Dashboard Ejecutivo')

@section('content')
{{-- STATS --}}
<div class="row g-3 mb-4">
    @php
    $statItems = [
        ['icon'=>'bi-collection-fill','color'=>'rgba(20,184,166,.2)','icolor'=>'#14b8a6','value'=>$stats['total'],'label'=>'Total Equipos','link'=>route('equipment.index')],
        ['icon'=>'bi-check-circle-fill','color'=>'rgba(45,212,191,.2)','icolor'=>'#2dd4bf','value'=>$stats['available'],'label'=>'Disponibles','link'=>route('equipment.index',['status'=>'available'])],
        ['icon'=>'bi-person-fill-check','color'=>'rgba(249,115,22,.2)','icolor'=>'#f97316','value'=>$stats['assigned'],'label'=>'Asignados','link'=>route('equipment.index',['status'=>'assigned'])],
        ['icon'=>'bi-tools','color'=>'rgba(234,88,12,.2)','icolor'=>'#ea580c','value'=>$stats['maintenance'],'label'=>'En Reparación','link'=>route('equipment.index',['status'=>'maintenance'])],
        ['icon'=>'bi-exclamation-triangle-fill','color'=>'rgba(239,68,68,.2)','icolor'=>'#ef4444','value'=>$stats['lost'],'label'=>'Extraviados','link'=>route('equipment.index',['status'=>'lost'])],
        ['icon'=>'bi-people-fill','color'=>'rgba(255,255,255,.08)','icolor'=>'#ffffff','value'=>$stats['collaborators'],'label'=>'Colaboradores','link'=>route('collaborators.index')],
    ];
    @endphp
    @foreach($statItems as $s)
    <div class="col-6 col-md-4 col-xl-2">
        <a href="{{ $s['link'] }}" class="stat-card d-block text-decoration-none">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div class="stat-icon" style="background:{{ $s['color'] }}">
                    <i class="bi {{ $s['icon'] }}" style="color:{{ $s['icolor'] }}"></i>
                </div>
            </div>
            <div class="stat-value" style="color:{{ $s['icolor'] }}">{{ number_format($s['value']) }}</div>
            <div class="stat-label">{{ $s['label'] }}</div>
        </a>
    </div>
    @endforeach
</div>

{{-- VALUE + CHARTS --}}
<div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
        <div class="stat-card h-100" style="border-color:rgba(20,184,166,.3);background:linear-gradient(135deg,#122420,#0e2e2a)">
            <div class="stat-icon mb-2" style="background:rgba(20,184,166,.2)">
                <i class="bi bi-currency-dollar" style="color:#14b8a6;font-size:1.5rem"></i>
            </div>
            <div class="stat-value" style="color:#2dd4bf;font-size:1.5rem">
                ${{ number_format($stats['total_value'], 2) }}
            </div>
            <div class="stat-label">Valor Total del Inventario</div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-pie-chart me-2"></i>Por Estado</div>
            <div class="card-body">
                @foreach(['available'=>['Disponibles','#2dd4bf'],'assigned'=>['Asignados','#f97316'],'maintenance'=>['Reparación','#ea580c'],'damaged'=>['Dañados','#ef4444'],'lost'=>['Extraviados','#fca5a5'],'retired'=>['Retirados','#7ab8b2']] as $k=>$v)
                @if(($byStatus[$k] ?? 0) > 0)
                <div class="d-flex justify-content-between align-items-center mb-2" style="font-size:.85rem">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:8px;height:8px;border-radius:50%;background:{{ $v[1] }}"></div>
                        {{ $v[0] }}
                    </div>
                    <span style="color:{{ $v[1] }};font-weight:600">{{ $byStatus[$k] ?? 0 }}</span>
                </div>
                @endif
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-grid me-2"></i>Por Tipo</div>
            <div class="card-body">
                @php $typeLabels = \App\Models\Equipment::$types; $colors = ['#14b8a6','#f97316','#2dd4bf','#ea580c','#ffffff','#fdba74','#5eead4','#fb923c','#7ab8b2']; $ci=0; @endphp
                @foreach($byType as $type => $count)
                <div class="d-flex justify-content-between align-items-center mb-2" style="font-size:.85rem">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:8px;height:8px;border-radius:50%;background:{{ $colors[$ci % count($colors)] }}"></div>
                        {{ $typeLabels[$type] ?? $type }}
                    </div>
                    <span style="color:{{ $colors[$ci % count($colors)] }};font-weight:600">{{ $count }}</span>
                </div>
                @php $ci++ @endphp
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- ALERTS + ACTIVITY --}}
<div class="row g-3">
    {{-- Warranty expiring --}}
    <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="bi bi-shield-exclamation text-warning me-2"></i>Garantías por Vencer (30 días)</span>
                <a href="{{ route('reports.warranty-expiring') }}" class="btn btn-sm" style="background:var(--bg-hover);border:1px solid var(--border);font-size:.78rem">Ver todo</a>
            </div>
            <div class="card-body p-0">
                @if($warrantyExpiring->isEmpty())
                    <div class="text-center py-4" style="color:var(--text-muted)">
                        <i class="bi bi-shield-check fs-3 d-block mb-2"></i>Sin garantías por vencer
                    </div>
                @else
                <div class="table-responsive">
                <table class="table mb-0">
                    <tbody>
                    @foreach($warrantyExpiring as $eq)
                    <tr>
                        <td>
                            <a href="{{ route('equipment.show', $eq) }}" class="fw-600 text-decoration-none" style="color:var(--text)">
                                {{ $eq->brand }} {{ $eq->model }}
                            </a>
                            <div style="font-size:.75rem;color:var(--text-muted)">{{ $eq->serial_number }}</div>
                        </td>
                        <td class="text-end">
                            <span style="color:{{ $eq->warranty_expiry->diffInDays(now()) <= 10 ? '#ef4444' : '#f97316' }};font-size:.85rem;font-weight:600">
                                {{ $eq->warranty_expiry->format('d/m/Y') }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Recent activity --}}
    <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="bi bi-clock-history me-2"></i>Actividad Reciente</span>
                <a href="{{ route('activity-logs.index') }}" class="btn btn-sm" style="background:var(--bg-hover);border:1px solid var(--border);font-size:.78rem">Ver todo</a>
            </div>
            <div class="card-body p-0">
                @if($recentActivity->isEmpty())
                    <div class="text-center py-4" style="color:var(--text-muted)">Sin actividad reciente</div>
                @else
                <div class="table-responsive">
                <table class="table mb-0">
                    <tbody>
                    @foreach($recentActivity as $log)
                    <tr>
                        <td style="font-size:.85rem">
                            <span class="badge rounded-pill" style="background:rgba(20,184,166,.15);color:var(--teal-light,#2dd4bf);font-size:.72rem">{{ $log->action }}</span>
                            <div class="mt-1">{{ $log->description }}</div>
                            <div style="font-size:.75rem;color:var(--text-muted)">
                                {{ $log->user?->name ?? 'Sistema' }} · {{ $log->created_at->diffForHumans() }}
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Overdue assignments --}}
    @if($overdueAssignments->isNotEmpty())
    <div class="col-12">
        <div class="card" style="border-color:rgba(239,68,68,.3)">
            <div class="card-header" style="border-color:rgba(239,68,68,.3)">
                <i class="bi bi-exclamation-circle-fill text-danger me-2"></i>
                Asignaciones Vencidas ({{ $overdueAssignments->count() }})
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                <table class="table mb-0">
                    <thead><tr>
                        <th>Equipo</th><th>Colaborador</th><th>Fecha Estimada</th><th>Días Vencido</th><th></th>
                    </tr></thead>
                    <tbody>
                    @foreach($overdueAssignments as $a)
                    <tr>
                        <td>{{ $a->equipment->brand }} {{ $a->equipment->model }}</td>
                        <td>{{ $a->collaborator->full_name }}</td>
                        <td style="color:var(--danger)">{{ $a->expected_return->format('d/m/Y') }}</td>
                        <td><span style="color:var(--danger);font-weight:600">{{ $a->expected_return->diffInDays(now()) }} días</span></td>
                        <td><a href="{{ route('assignments.show', $a) }}" class="btn btn-sm" style="background:rgba(239,68,68,.15);color:var(--danger);border:none">Ver</a></td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
