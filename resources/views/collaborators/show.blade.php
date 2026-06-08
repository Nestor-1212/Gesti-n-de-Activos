@extends('layouts.app')
@section('title', $collaborator->full_name)
@section('page-title', 'Perfil del Colaborador')

@section('content')
<div class="d-flex align-items-center gap-2 mb-4 flex-wrap">
    <a href="{{ route('collaborators.index') }}" class="btn btn-sm" style="background:var(--bg-card);border:1px solid var(--border)"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">{{ $collaborator->full_name }}</h5>
    <div class="ms-auto d-flex gap-2">
        <a href="{{ route('assignments.create', ['collaborator_id'=>$collaborator->id]) }}" class="btn btn-sm btn-primary"><i class="bi bi-phone me-1"></i>Asignar Equipo</a>
        <a href="{{ route('collaborators.edit', $collaborator) }}" class="btn btn-sm" style="background:rgba(20,184,166,.15);border:none;color:var(--accent)"><i class="bi bi-pencil me-1"></i>Editar</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-12 col-md-4">
        <div class="card text-center mb-3">
            <div class="card-body py-4">
                @if($collaborator->photo)
                    <img src="{{ asset('storage/'.$collaborator->photo) }}" class="rounded-circle mb-3" style="width:100px;height:100px;object-fit:cover">
                @else
                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold mx-auto mb-3" style="width:100px;height:100px;background:linear-gradient(135deg,var(--accent),#f97316);font-size:2rem">
                        {{ strtoupper(substr($collaborator->first_name,0,1).substr($collaborator->last_name,0,1)) }}
                    </div>
                @endif
                <h5 class="fw-bold mb-1">{{ $collaborator->full_name }}</h5>
                <div style="color:var(--text-muted);font-size:.85rem">{{ $collaborator->position ?? 'Sin cargo' }}</div>
                <div class="mt-2">
                    <span class="badge rounded-pill {{ $collaborator->status==='active'?'badge-active':'badge-retired' }}">
                        {{ $collaborator->status === 'active' ? 'Activo' : 'Inactivo' }}
                    </span>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><i class="bi bi-info-circle me-2"></i>Información</div>
            <div class="card-body" style="font-size:.85rem">
                @php $info = ['Cédula'=>$collaborator->cedula,'Departamento'=>$collaborator->department?->name??'—','Correo'=>$collaborator->email??'—','Teléfono'=>$collaborator->phone??'—','Supervisor'=>$collaborator->supervisor??'—']; @endphp
                @foreach($info as $l=>$v)
                <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid var(--border)">
                    <span style="color:var(--text-muted)">{{ $l }}</span>
                    <span style="font-weight:500">{{ $v }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-12 col-md-8">
        <div class="card">
            <div class="card-header"><i class="bi bi-arrow-left-right me-2"></i>Historial de Asignaciones</div>
            @if($collaborator->assignments->isEmpty())
                <div class="card-body text-center py-4" style="color:var(--text-muted)">
                    <i class="bi bi-phone fs-2 d-block mb-2"></i>Sin equipos asignados
                </div>
            @else
            <div class="table-responsive">
            <table class="table mb-0">
                <thead><tr><th>Equipo</th><th>Entrega</th><th>Devolución</th><th>Estado</th><th></th></tr></thead>
                <tbody>
                @foreach($collaborator->assignments->sortByDesc('assigned_at') as $a)
                <tr>
                    <td>
                        <div class="fw-600" style="font-size:.9rem">{{ $a->equipment->brand }} {{ $a->equipment->model }}</div>
                        <div style="font-size:.75rem;color:var(--text-muted)">{{ $a->equipment->serial_number }}</div>
                    </td>
                    <td style="font-size:.85rem">{{ $a->assigned_at->format('d/m/Y') }}</td>
                    <td style="font-size:.85rem">{{ $a->returned_at?->format('d/m/Y') ?? ($a->expected_return?->format('d/m/Y').' ★') ?? '—' }}</td>
                    <td><span class="badge rounded-pill badge-{{ $a->status }}" style="font-size:.72rem">{{ $a->status === 'active' ? 'Activo' : ($a->status === 'returned' ? 'Devuelto' : 'Vencido') }}</span></td>
                    <td><a href="{{ route('assignments.show', $a) }}" class="btn btn-sm" style="background:var(--bg-hover);border:1px solid var(--border);padding:.2rem .5rem;font-size:.78rem">Ver</a></td>
                </tr>
                @endforeach
                </tbody>
            </table>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
