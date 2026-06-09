@extends('layouts.app')
@section('title', $equipment->brand.' '.$equipment->model)
@section('page-title', 'Detalle del Equipo')

@section('content')
<div class="d-flex align-items-center gap-2 mb-4 flex-wrap">
    <a href="{{ route('equipment.index') }}" class="btn btn-sm" style="background:var(--bg-card);border:1px solid var(--border)">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h5 class="mb-0 fw-bold">{{ $equipment->brand }} {{ $equipment->model }}</h5>
    <span class="badge rounded-pill {{ $equipment->status?->badgeClass() ?? '' }}">{{ $equipment->status_label }}</span>
    <div class="ms-auto d-flex gap-2">
        <a href="{{ route('equipment.qr', $equipment) }}" class="btn btn-sm" style="background:var(--bg-card);border:1px solid var(--border)" target="_blank">
            <i class="bi bi-qr-code me-1"></i>QR
        </a>
        @if($equipment->status === \App\Enums\EquipmentStatus::Available)
        <a href="{{ route('assignments.create', ['equipment_id'=>$equipment->id]) }}" class="btn btn-sm btn-primary">
            <i class="bi bi-person-plus me-1"></i>Asignar
        </a>
        @endif
        <a href="{{ route('equipment.edit', $equipment) }}" class="btn btn-sm" style="background:rgba(20,184,166,.15);border:none;color:var(--accent)">
            <i class="bi bi-pencil me-1"></i>Editar
        </a>
    </div>
</div>

<div class="row g-3">
    {{-- LEFT --}}
    <div class="col-12 col-lg-8">
        {{-- Photos --}}
        @if($equipment->photos->isNotEmpty())
        <div class="card mb-3">
            <div class="card-body">
                <div class="row g-2">
                    @foreach($equipment->photos as $photo)
                    <div class="col-4 col-md-3">
                        <img src="{{ asset('storage/'.$photo->path) }}" class="img-fluid rounded" style="width:100%;height:100px;object-fit:cover">
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- Info tabs --}}
        <div class="card mb-3">
            <div class="card-header p-0">
                <ul class="nav nav-tabs card-header-tabs border-0 px-3 pt-2" id="eqTabs">
                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tab-info">Información</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-assignments">Historial</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-accessories">Accesorios</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-maintenance">Mantenimiento</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-docs">Documentos</a></li>
                </ul>
            </div>
            <div class="tab-content card-body">
                {{-- Info --}}
                <div class="tab-pane fade show active" id="tab-info">
                    <div class="row g-3">
                        @php
                        $fields = [
                            'Marca'=>$equipment->brand,'Modelo'=>$equipment->model,
                            'Número de Serie'=>$equipment->serial_number,'Tipo'=>$equipment->type_label,
                            'IMEI 1'=>$equipment->imei1??'—','IMEI 2'=>$equipment->imei2??'—',
                            'Número Telefónico'=>$equipment->phone_number??'—','Compañía'=>$equipment->carrier??'—',
                            'Dirección IP'=>$equipment->ip_address??'—','Dirección MAC'=>$equipment->mac_address??'—',
                            'Sistema Operativo'=>$equipment->operating_system??'—','RAM'=>$equipment->ram??'—',
                            'Almacenamiento'=>$equipment->storage_capacity??'—',
                        ];
                        @endphp
                        @foreach($fields as $label=>$val)
                        <div class="col-6 col-md-4">
                            <div style="font-size:.75rem;color:var(--text-muted)">{{ $label }}</div>
                            <div style="font-size:.9rem;font-weight:500">{{ $val }}</div>
                        </div>
                        @endforeach
                    </div>
                    @if($equipment->notes)
                    <div class="mt-3 p-3 rounded-3" style="background:var(--bg-hover)">
                        <div style="font-size:.75rem;color:var(--text-muted);margin-bottom:.25rem">Observaciones</div>
                        <p class="mb-0" style="font-size:.88rem">{{ $equipment->notes }}</p>
                    </div>
                    @endif
                </div>

                {{-- Historial --}}
                <div class="tab-pane fade" id="tab-assignments">
                    @if($equipment->assignments->isEmpty())
                        <p style="color:var(--text-muted)" class="text-center py-3">Sin historial de asignaciones</p>
                    @else
                    <div class="table-responsive">
                    <table class="table">
                        <thead><tr><th>Colaborador</th><th>Entrega</th><th>Devolución</th><th>Estado</th><th></th></tr></thead>
                        <tbody>
                        @foreach($equipment->assignments->sortByDesc('assigned_at') as $a)
                        <tr>
                            <td>{{ $a->collaborator->full_name }}</td>
                            <td>{{ $a->assigned_at->format('d/m/Y') }}</td>
                            <td>{{ $a->returned_at?->format('d/m/Y') ?? ($a->expected_return?->format('d/m/Y').' *') ?? '—' }}</td>
                            <td><span class="badge rounded-pill badge-{{ $a->status }}" style="font-size:.72rem">{{ $a->status === 'active' ? 'Activo' : ($a->status === 'returned' ? 'Devuelto' : 'Vencido') }}</span></td>
                            <td><a href="{{ route('assignments.show', $a) }}" class="btn btn-sm" style="background:var(--bg-hover);border:1px solid var(--border);padding:.2rem .5rem;font-size:.78rem">Ver</a></td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                    </div>
                    @endif
                </div>

                {{-- Accesorios --}}
                <div class="tab-pane fade" id="tab-accessories">
                    <div class="d-flex justify-content-end mb-2">
                        <a href="{{ route('maintenance.create', ['equipment_id'=>$equipment->id]) }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-lg me-1"></i>Agregar Accesorio
                        </a>
                    </div>
                    @if($equipment->accessories->isEmpty())
                        <p style="color:var(--text-muted)" class="text-center py-3">Sin accesorios registrados</p>
                    @else
                    <div class="table-responsive">
                    <table class="table"><thead><tr><th>Nombre</th><th>Tipo</th><th>Marca</th><th>Estado</th></tr></thead>
                    <tbody>
                    @foreach($equipment->accessories as $acc)
                    <tr>
                        <td>{{ $acc->name }}</td>
                        <td>{{ \App\Models\Accessory::$types[$acc->type] ?? $acc->type }}</td>
                        <td>{{ $acc->brand ?? '—' }}</td>
                        <td><span class="badge rounded-pill badge-{{ $acc->status }}" style="font-size:.72rem">{{ $acc->status }}</span></td>
                    </tr>
                    @endforeach
                    </tbody></table>
                    </div>
                    @endif
                </div>

                {{-- Mantenimiento --}}
                <div class="tab-pane fade" id="tab-maintenance">
                    <div class="d-flex justify-content-end mb-2">
                        <a href="{{ route('maintenance.create', ['equipment_id'=>$equipment->id]) }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-lg me-1"></i>Nuevo Mantenimiento
                        </a>
                    </div>
                    @if($equipment->maintenanceRecords->isEmpty())
                        <p style="color:var(--text-muted)" class="text-center py-3">Sin registros de mantenimiento</p>
                    @else
                    <div class="table-responsive">
                    <table class="table"><thead><tr><th>Tipo</th><th>Descripción</th><th>Fecha</th><th>Estado</th></tr></thead>
                    <tbody>
                    @foreach($equipment->maintenanceRecords->sortByDesc('maintenance_date') as $m)
                    <tr>
                        <td><span class="badge rounded-pill" style="background:rgba(249,115,22,.15);color:var(--warning);font-size:.72rem">{{ ['preventive'=>'Preventivo','corrective'=>'Correctivo','upgrade'=>'Actualización'][$m->type] }}</span></td>
                        <td style="font-size:.85rem">{{ Str::limit($m->description, 60) }}</td>
                        <td style="font-size:.85rem">{{ $m->maintenance_date->format('d/m/Y') }}</td>
                        <td><span class="badge rounded-pill" style="font-size:.72rem;background:{{ $m->status==='completed'?'rgba(34,197,94,.15)':'rgba(249,115,22,.15)' }};color:{{ $m->status==='completed'?'#22c55e':'#f97316' }}">{{ ['pending'=>'Pendiente','in_progress'=>'En Proceso','completed'=>'Completado'][$m->status] }}</span></td>
                    </tr>
                    @endforeach
                    </tbody></table>
                    </div>
                    @endif
                </div>

                {{-- Documentos --}}
                <div class="tab-pane fade" id="tab-docs">
                    @if($equipment->documents->isEmpty())
                        <p style="color:var(--text-muted)" class="text-center py-3">Sin documentos adjuntos</p>
                    @else
                    <div class="row g-2">
                    @foreach($equipment->documents as $doc)
                    <div class="col-12 col-md-6">
                        <a href="{{ asset('storage/'.$doc->path) }}" target="_blank" class="d-flex align-items-center gap-2 p-2 rounded-3 text-decoration-none" style="background:var(--bg-hover)">
                            <i class="bi bi-file-earmark-text fs-4" style="color:var(--accent)"></i>
                            <div>
                                <div style="font-size:.85rem;color:var(--text)">{{ $doc->name }}</div>
                                <div style="font-size:.75rem;color:var(--text-muted)">{{ \App\Models\EquipmentDocument::$types[$doc->type] ?? $doc->type }}</div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT --}}
    <div class="col-12 col-lg-4">
        {{-- QR --}}
        <div class="card mb-3 text-center">
            <div class="card-header"><i class="bi bi-qr-code me-2"></i>Código QR</div>
            <div class="card-body">
                <div class="d-flex justify-content-center" style="max-width:190px;margin:0 auto;background:#fff;padding:8px;border-radius:8px">{!! $qrCode !!}</div>
                <div class="mt-2" style="font-size:.78rem;color:var(--text-muted)">{{ $equipment->qr_code }}</div>
                <a href="{{ route('equipment.qr', $equipment) }}" class="btn btn-sm w-100 mt-2" style="background:var(--bg-hover);border:1px solid var(--border)" target="_blank">
                    <i class="bi bi-download me-1"></i>Descargar QR
                </a>
            </div>
        </div>

        {{-- Compra --}}
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-receipt me-2"></i>Datos de Compra</div>
            <div class="card-body">
                @php
                $purchase = [
                    'Proveedor'=>$equipment->supplier??'—',
                    'Fecha de Compra'=>$equipment->purchase_date?->format('d/m/Y')??'—',
                    'Garantía hasta'=>$equipment->warranty_expiry?->format('d/m/Y')??'—',
                    'Costo'=>$equipment->cost ? '$'.number_format($equipment->cost,2) : '—',
                    'Código de Barras'=>$equipment->barcode??'—',
                ];
                @endphp
                @foreach($purchase as $label=>$val)
                <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid var(--border);font-size:.85rem">
                    <span style="color:var(--text-muted)">{{ $label }}</span>
                    <span style="font-weight:500">{{ $val }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Current assignment --}}
        @if($equipment->activeAssignment)
        <div class="card" style="border-color:rgba(20,184,166,.3)">
            <div class="card-header" style="border-color:rgba(20,184,166,.3)">
                <i class="bi bi-person-fill-check me-2" style="color:var(--accent)"></i>Asignación Actual
            </div>
            <div class="card-body" style="font-size:.85rem">
                @php $a = $equipment->activeAssignment @endphp
                <div class="fw-bold mb-1">{{ $a->collaborator->full_name }}</div>
                <div style="color:var(--text-muted)">{{ $a->collaborator->position }}</div>
                <div class="mt-2">
                    <div class="d-flex justify-content-between py-1"><span style="color:var(--text-muted)">Entrega</span><span>{{ $a->assigned_at->format('d/m/Y') }}</span></div>
                    @if($a->expected_return)
                    <div class="d-flex justify-content-between py-1"><span style="color:var(--text-muted)">Devolución est.</span><span style="color:{{ $a->expected_return->isPast()?'#ef4444':'var(--text)' }}">{{ $a->expected_return->format('d/m/Y') }}</span></div>
                    @endif
                </div>
                <form method="POST" action="{{ route('assignments.return', $a) }}" class="mt-2">
                    @csrf
                    <button type="button" class="btn btn-sm w-100" style="background:rgba(34,197,94,.15);color:#22c55e;border:none"
                        onclick="confirmDelete(this.closest('form'), '¿Registrar devolución?', 'Se registrará la devolución de este equipo por parte de {{ addslashes($a->collaborator->full_name) }}.')">
                        <i class="bi bi-arrow-return-left me-1"></i>Registrar Devolución
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
