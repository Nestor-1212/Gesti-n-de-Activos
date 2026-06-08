@extends('layouts.app')
@section('title', 'Nuevo Equipo')
@section('page-title', 'Registrar Equipo')

@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('equipment.index') }}" class="btn btn-sm" style="background:var(--bg-card);border:1px solid var(--border)">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h5 class="mb-0 fw-bold">Registrar Nuevo Equipo</h5>
</div>

<form method="POST" action="{{ route('equipment.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="row g-3">
        {{-- Info básica --}}
        <div class="col-12 col-lg-8">
            <div class="card mb-3">
                <div class="card-header"><i class="bi bi-phone me-2"></i>Información del Equipo</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label">Marca *</label>
                            <input type="text" name="brand" class="form-control" value="{{ old('brand') }}" placeholder="Samsung, Apple, Lenovo…" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Modelo *</label>
                            <input type="text" name="model" class="form-control" value="{{ old('model') }}" placeholder="Galaxy S23, ThinkPad…" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Número de Serie *</label>
                            <input type="text" name="serial_number" class="form-control" value="{{ old('serial_number') }}" placeholder="SN-XXXXXXXXX" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Tipo *</label>
                            <select name="type" class="form-select" required>
                                @foreach(\App\Models\Equipment::$types as $k=>$v)
                                    <option value="{{ $k }}" {{ old('type')==$k?'selected':'' }}>{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Estado *</label>
                            <select name="status" class="form-select" required>
                                @foreach(\App\Models\Equipment::$statuses as $k=>$v)
                                    <option value="{{ $k }}" {{ old('status','available')==$k?'selected':'' }}>{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Sistema Operativo</label>
                            <input type="text" name="operating_system" class="form-control" value="{{ old('operating_system') }}" placeholder="Android 14, iOS 17, Windows 11…">
                        </div>
                        <div class="col-6">
                            <label class="form-label">RAM</label>
                            <input type="text" name="ram" class="form-control" value="{{ old('ram') }}" placeholder="8GB, 16GB…">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Almacenamiento</label>
                            <input type="text" name="storage_capacity" class="form-control" value="{{ old('storage_capacity') }}" placeholder="128GB, 512GB SSD…">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header"><i class="bi bi-phone-vibrate me-2"></i>Datos de Telefonía / Red</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label">IMEI 1</label>
                            <input type="text" name="imei1" class="form-control" value="{{ old('imei1') }}" placeholder="350000000000000">
                        </div>
                        <div class="col-6">
                            <label class="form-label">IMEI 2</label>
                            <input type="text" name="imei2" class="form-control" value="{{ old('imei2') }}" placeholder="350000000000001">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Número Telefónico</label>
                            <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number') }}" placeholder="+507 6000-0000">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Compañía Telefónica</label>
                            <input type="text" name="carrier" class="form-control" value="{{ old('carrier') }}" placeholder="Claro, Movistar, Cable & Wireless…">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Dirección IP</label>
                            <input type="text" name="ip_address" class="form-control" value="{{ old('ip_address') }}" placeholder="192.168.1.100">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Dirección MAC</label>
                            <input type="text" name="mac_address" class="form-control" value="{{ old('mac_address') }}" placeholder="AA:BB:CC:DD:EE:FF">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><i class="bi bi-receipt me-2"></i>Datos de Compra</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label">Fecha de Compra</label>
                            <input type="date" name="purchase_date" class="form-control" value="{{ old('purchase_date') }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Vencimiento de Garantía</label>
                            <input type="date" name="warranty_expiry" class="form-control" value="{{ old('warranty_expiry') }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Proveedor</label>
                            <input type="text" name="supplier" class="form-control" value="{{ old('supplier') }}" placeholder="Nombre del proveedor">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Costo ($)</label>
                            <input type="number" name="cost" class="form-control" value="{{ old('cost') }}" step="0.01" min="0" placeholder="0.00">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Observaciones</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Notas adicionales…">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Photos --}}
        <div class="col-12 col-lg-4">
            <div class="card mb-3">
                <div class="card-header"><i class="bi bi-camera me-2"></i>Fotografías</div>
                <div class="card-body">
                    <div class="border-2 border-dashed rounded-3 text-center p-4" style="border-color:var(--border);cursor:pointer" onclick="document.getElementById('photos').click()">
                        <i class="bi bi-cloud-upload fs-2 d-block mb-2" style="color:var(--text-muted)"></i>
                        <div style="color:var(--text-muted);font-size:.85rem">Click para seleccionar imágenes</div>
                        <div style="color:var(--text-muted);font-size:.75rem">JPG, PNG, WEBP — Máx. 5MB c/u</div>
                    </div>
                    <input type="file" id="photos" name="photos[]" multiple accept="image/*" class="d-none" onchange="previewPhotos(this)">
                    <div id="photo-preview" class="row g-2 mt-2"></div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><i class="bi bi-info-circle me-2"></i>Ayuda</div>
                <div class="card-body" style="font-size:.83rem;color:var(--text-muted)">
                    <p class="mb-2">El código QR se generará automáticamente al registrar el equipo.</p>
                    <p class="mb-2">Puedes usar el escáner para buscar equipos por código de barras o QR.</p>
                    <p class="mb-0">Los campos marcados con * son obligatorios.</p>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('equipment.index') }}" class="btn" style="background:var(--bg-card);border:1px solid var(--border)">Cancelar</a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save me-1"></i>Registrar Equipo
                </button>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
function previewPhotos(input) {
    const preview = document.getElementById('photo-preview');
    preview.innerHTML = '';
    for (const file of input.files) {
        const reader = new FileReader();
        reader.onload = e => {
            const col = document.createElement('div');
            col.className = 'col-6';
            col.innerHTML = `<img src="${e.target.result}" class="img-fluid rounded" style="width:100%;height:80px;object-fit:cover">`;
            preview.appendChild(col);
        };
        reader.readAsDataURL(file);
    }
}
</script>
@endpush
@endsection
