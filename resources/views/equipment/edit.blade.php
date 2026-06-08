@extends('layouts.app')
@section('title', 'Editar Equipo')
@section('page-title', 'Editar Equipo')

@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('equipment.show', $equipment) }}" class="btn btn-sm" style="background:var(--bg-card);border:1px solid var(--border)">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h5 class="mb-0 fw-bold">Editar: {{ $equipment->brand }} {{ $equipment->model }}</h5>
</div>

<form method="POST" action="{{ route('equipment.update', $equipment) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="row g-3">
        <div class="col-12 col-lg-8">
            <div class="card mb-3">
                <div class="card-header"><i class="bi bi-phone me-2"></i>Información del Equipo</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label">Marca *</label>
                            <input type="text" name="brand" class="form-control" value="{{ old('brand', $equipment->brand) }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Modelo *</label>
                            <input type="text" name="model" class="form-control" value="{{ old('model', $equipment->model) }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Número de Serie *</label>
                            <input type="text" name="serial_number" class="form-control" value="{{ old('serial_number', $equipment->serial_number) }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Tipo *</label>
                            <select name="type" class="form-select" required>
                                @foreach(\App\Models\Equipment::$types as $k=>$v)
                                    <option value="{{ $k }}" {{ old('type',$equipment->type)==$k?'selected':'' }}>{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Estado *</label>
                            <select name="status" class="form-select" required>
                                @foreach(\App\Models\Equipment::$statuses as $k=>$v)
                                    <option value="{{ $k }}" {{ old('status',$equipment->status)==$k?'selected':'' }}>{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Sistema Operativo</label>
                            <input type="text" name="operating_system" class="form-control" value="{{ old('operating_system', $equipment->operating_system) }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label">RAM</label>
                            <input type="text" name="ram" class="form-control" value="{{ old('ram', $equipment->ram) }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Almacenamiento</label>
                            <input type="text" name="storage_capacity" class="form-control" value="{{ old('storage_capacity', $equipment->storage_capacity) }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label">IMEI 1</label>
                            <input type="text" name="imei1" class="form-control" value="{{ old('imei1', $equipment->imei1) }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label">IMEI 2</label>
                            <input type="text" name="imei2" class="form-control" value="{{ old('imei2', $equipment->imei2) }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Número Telefónico</label>
                            <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', $equipment->phone_number) }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Compañía Telefónica</label>
                            <input type="text" name="carrier" class="form-control" value="{{ old('carrier', $equipment->carrier) }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Dirección IP</label>
                            <input type="text" name="ip_address" class="form-control" value="{{ old('ip_address', $equipment->ip_address) }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Dirección MAC</label>
                            <input type="text" name="mac_address" class="form-control" value="{{ old('mac_address', $equipment->mac_address) }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Fecha de Compra</label>
                            <input type="date" name="purchase_date" class="form-control" value="{{ old('purchase_date', $equipment->purchase_date?->format('Y-m-d')) }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Vencimiento de Garantía</label>
                            <input type="date" name="warranty_expiry" class="form-control" value="{{ old('warranty_expiry', $equipment->warranty_expiry?->format('Y-m-d')) }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Proveedor</label>
                            <input type="text" name="supplier" class="form-control" value="{{ old('supplier', $equipment->supplier) }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Costo ($)</label>
                            <input type="number" name="cost" class="form-control" value="{{ old('cost', $equipment->cost) }}" step="0.01" min="0">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Observaciones</label>
                            <textarea name="notes" class="form-control" rows="3">{{ old('notes', $equipment->notes) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="card mb-3">
                <div class="card-header"><i class="bi bi-camera me-2"></i>Agregar Fotos</div>
                <div class="card-body">
                    @if($equipment->photos->isNotEmpty())
                    <div class="row g-2 mb-3">
                        @foreach($equipment->photos as $photo)
                        <div class="col-4">
                            <img src="{{ asset('storage/'.$photo->path) }}" class="img-fluid rounded" style="width:100%;height:70px;object-fit:cover">
                        </div>
                        @endforeach
                    </div>
                    @endif
                    <label class="form-label">Nuevas Fotos</label>
                    <input type="file" name="photos[]" multiple accept="image/*" class="form-control">
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('equipment.show', $equipment) }}" class="btn" style="background:var(--bg-card);border:1px solid var(--border)">Cancelar</a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save me-1"></i>Guardar Cambios
                </button>
            </div>
        </div>
    </div>
</form>
@endsection
