@extends('layouts.app')
@section('title', 'Asignación #'.$assignment->id)
@section('page-title', 'Detalle de Asignación')
@section('content')
<div class="d-flex align-items-center gap-2 mb-4 flex-wrap">
    <a href="{{ route('assignments.index') }}" class="btn btn-sm" style="background:var(--bg-card);border:1px solid var(--border)"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Asignación #{{ $assignment->id }}</h5>
    <span class="badge rounded-pill badge-{{ $assignment->status }}" style="font-size:.8rem">
        {{ $assignment->status === 'active' ? 'Activa' : ($assignment->status === 'returned' ? 'Devuelta' : 'Vencida') }}
    </span>
    <div class="ms-auto d-flex gap-2 flex-wrap">
        <a href="{{ route('assignments.acta', $assignment) }}" class="btn btn-sm" style="background:var(--bg-card);border:1px solid var(--border)" target="_blank">
            <i class="bi bi-file-pdf me-1"></i>Acta PDF
        </a>
        @if($assignment->status === 'active')
        <button class="btn btn-sm" style="background:rgba(34,197,94,.15);color:#22c55e;border:none" data-bs-toggle="modal" data-bs-target="#returnModal">
            <i class="bi bi-arrow-return-left me-1"></i>Registrar Devolución
        </button>
        @endif
    </div>
</div>

<div class="row g-3">
    <div class="col-12 col-md-6">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-phone me-2"></i>Equipo</div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    @if($assignment->equipment->mainPhoto)
                        <img src="{{ asset('storage/'.$assignment->equipment->mainPhoto->path) }}" class="rounded" style="width:60px;height:60px;object-fit:cover">
                    @else
                        <div class="rounded d-flex align-items-center justify-content-center" style="width:60px;height:60px;background:var(--bg-hover)"><i class="bi bi-phone fs-3" style="color:var(--text-muted)"></i></div>
                    @endif
                    <div>
                        <div class="fw-bold">{{ $assignment->equipment->brand }} {{ $assignment->equipment->model }}</div>
                        <div style="font-size:.8rem;color:var(--text-muted)">{{ $assignment->equipment->serial_number }}</div>
                    </div>
                </div>
                <a href="{{ route('equipment.show', $assignment->equipment) }}" class="btn btn-sm w-100" style="background:var(--bg-hover);border:1px solid var(--border)">
                    <i class="bi bi-eye me-1"></i>Ver Equipo
                </a>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-person me-2"></i>Colaborador</div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width:60px;height:60px;background:linear-gradient(135deg,var(--accent),#f97316);font-size:1.2rem;flex-shrink:0">
                        {{ strtoupper(substr($assignment->collaborator->first_name,0,1).substr($assignment->collaborator->last_name,0,1)) }}
                    </div>
                    <div>
                        <div class="fw-bold">{{ $assignment->collaborator->full_name }}</div>
                        <div style="font-size:.8rem;color:var(--text-muted)">{{ $assignment->collaborator->position }} — {{ $assignment->collaborator->department?->name }}</div>
                    </div>
                </div>
                <a href="{{ route('collaborators.show', $assignment->collaborator) }}" class="btn btn-sm w-100" style="background:var(--bg-hover);border:1px solid var(--border)">
                    <i class="bi bi-person me-1"></i>Ver Colaborador
                </a>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-header"><i class="bi bi-clipboard me-2"></i>Detalles de la Asignación</div>
            <div class="card-body">
                <div class="row g-3" style="font-size:.88rem">
                    <div class="col-6 col-md-3"><div style="color:var(--text-muted);font-size:.75rem">Fecha Entrega</div><div class="fw-600">{{ $assignment->assigned_at->format('d/m/Y') }}</div></div>
                    <div class="col-6 col-md-3"><div style="color:var(--text-muted);font-size:.75rem">Fecha Est. Devolución</div><div class="fw-600" style="color:{{ $assignment->expected_return && $assignment->expected_return->isPast() && $assignment->status==='active'?'#ef4444':'var(--text)' }}">{{ $assignment->expected_return?->format('d/m/Y') ?? '—' }}</div></div>
                    <div class="col-6 col-md-3"><div style="color:var(--text-muted);font-size:.75rem">Devuelto el</div><div class="fw-600">{{ $assignment->returned_at?->format('d/m/Y') ?? '—' }}</div></div>
                    <div class="col-6 col-md-3"><div style="color:var(--text-muted);font-size:.75rem">Asignado por</div><div class="fw-600">{{ $assignment->assignedBy->name }}</div></div>
                    @if($assignment->reason)
                    <div class="col-12"><div style="color:var(--text-muted);font-size:.75rem">Motivo</div><div>{{ $assignment->reason }}</div></div>
                    @endif
                    @if($assignment->observations)
                    <div class="col-12"><div style="color:var(--text-muted);font-size:.75rem">Observaciones de Entrega</div><div class="p-3 rounded-3" style="background:var(--bg-hover)">{{ $assignment->observations }}</div></div>
                    @endif
                    @if($assignment->return_observations)
                    <div class="col-12"><div style="color:var(--text-muted);font-size:.75rem">Observaciones de Devolución</div><div class="p-3 rounded-3" style="background:var(--bg-hover)">{{ $assignment->return_observations }}</div></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Return modal --}}
@if($assignment->status === 'active')
<div class="modal fade" id="returnModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="background:var(--bg-card);border:1px solid var(--border)">
            <div class="modal-header" style="border-color:var(--border)">
                <h5 class="modal-title">Registrar Devolución</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('assignments.return', $assignment) }}">
                @csrf
                <div class="modal-body">
                    <label class="form-label">Observaciones de la Devolución</label>
                    <textarea name="return_observations" class="form-control" rows="4" placeholder="Estado del equipo al ser devuelto, daños observados, etc."></textarea>
                </div>
                <div class="modal-footer" style="border-color:var(--border)">
                    <button type="button" class="btn" data-bs-dismiss="modal" style="background:var(--bg-hover);border:1px solid var(--border)">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Confirmar Devolución</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
