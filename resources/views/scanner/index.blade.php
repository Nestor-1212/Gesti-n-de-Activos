@extends('layouts.app')
@section('title', 'Escáner')
@section('page-title', 'Escáner de Código QR / Barras')

@section('content')
<div class="row g-3">
    <div class="col-12 col-lg-7">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="bi bi-qr-code-scan me-2"></i>Cámara / Escáner</span>
                <div class="d-flex gap-2">
                    <button onclick="startCamera()" class="btn btn-sm btn-primary" id="startBtn"><i class="bi bi-camera me-1"></i>Activar Cámara</button>
                    <button onclick="stopCamera()" class="btn btn-sm d-none" id="stopBtn" style="background:rgba(239,68,68,.15);color:#ef4444;border:none"><i class="bi bi-camera-video-off me-1"></i>Detener</button>
                </div>
            </div>
            <div class="card-body text-center">
                <div id="reader" style="width:100%;max-width:500px;margin:0 auto;border-radius:12px;overflow:hidden;background:var(--bg-hover);min-height:280px;display:flex;align-items:center;justify-content:center;">
                    <div style="color:var(--text-muted)">
                        <i class="bi bi-qr-code fs-1 d-block mb-2"></i>
                        <div>Activa la cámara para escanear</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header"><i class="bi bi-keyboard me-2"></i>Búsqueda Manual</div>
            <div class="card-body">
                <div class="input-group">
                    <input type="text" id="manualCode" class="form-control" placeholder="Ingresa código de barras, IMEI, número de serie…" autocomplete="off">
                    <button class="btn btn-primary" onclick="lookupCode(document.getElementById('manualCode').value)">
                        <i class="bi bi-search me-1"></i>Buscar
                    </button>
                </div>
                <div style="font-size:.8rem;color:var(--text-muted);margin-top:.5rem">
                    También puedes usar un lector USB — simplemente escanea y el campo se llenará automáticamente.
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-5">
        <div class="card" id="resultCard" style="display:none!important">
            <div class="card-header" id="resultHeader"></div>
            <div class="card-body" id="resultBody"></div>
        </div>

        <div class="card" id="notFoundCard" style="display:none!important">
            <div class="card-body text-center py-4" style="color:var(--text-muted)">
                <i class="bi bi-search fs-2 d-block mb-2"></i>
                <div id="notFoundMsg">Equipo no encontrado</div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><i class="bi bi-lightbulb me-2"></i>Instrucciones</div>
            <div class="card-body" style="font-size:.83rem;color:var(--text-muted)">
                <ul class="mb-0 ps-3">
                    <li class="mb-1">Activa la cámara y apunta al código QR o código de barras del equipo.</li>
                    <li class="mb-1">Con lector USB: solo escanea el código, se buscará automáticamente.</li>
                    <li class="mb-1">La búsqueda funciona por: código de barras, código QR, IMEI o número de serie.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
#reader video { border-radius: 12px; }
#reader canvas { border-radius: 12px; }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
let html5QrCode = null;
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

function startCamera() {
    document.getElementById('startBtn').classList.add('d-none');
    document.getElementById('stopBtn').classList.remove('d-none');

    html5QrCode = new Html5Qrcode("reader");
    html5QrCode.start(
        { facingMode: "environment" },
        { fps: 10, qrbox: { width: 250, height: 250 } },
        (decodedText) => lookupCode(decodedText),
        () => {}
    ).catch(err => {
        showError('No se pudo acceder a la cámara: ' + err);
        stopCamera();
    });
}

function stopCamera() {
    if (html5QrCode) {
        html5QrCode.stop().then(() => {
            html5QrCode.clear();
            document.getElementById('reader').innerHTML = `<div style="color:var(--text-muted)"><i class="bi bi-qr-code fs-1 d-block mb-2"></i><div>Activa la cámara para escanear</div></div>`;
        });
    }
    document.getElementById('startBtn').classList.remove('d-none');
    document.getElementById('stopBtn').classList.add('d-none');
}

async function lookupCode(code) {
    if (!code.trim()) return;
    stopCamera();

    const res = await fetch('{{ route("scanner.lookup") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ code })
    });
    const data = await res.json();

    document.getElementById('resultCard').style.display = 'none';
    document.getElementById('notFoundCard').style.display = 'none';

    if (data.found) {
        const eq = data.equipment;
        const statusColors = { available:'#22c55e', assigned:'#14b8a6', maintenance:'#f97316', damaged:'#ef4444', lost:'#fb923c', retired:'#8892a4' };
        const color = statusColors[eq.status] || '#8892a4';

        document.getElementById('resultHeader').innerHTML = `<i class="bi bi-check-circle-fill me-2" style="color:${color}"></i>Equipo Encontrado`;
        document.getElementById('resultBody').innerHTML = `
            <h5 class="fw-bold mb-1">${eq.brand} ${eq.model}</h5>
            <div style="font-size:.85rem;color:var(--text-muted)">Serie: ${eq.serial}</div>
            <div class="mt-2 mb-3">
                <span class="badge rounded-pill" style="background:${color}22;color:${color};font-size:.85rem">${eq.status_label}</span>
            </div>
            ${eq.assigned_to ? `<div style="font-size:.85rem;color:var(--text-muted)">Asignado a: <strong style="color:var(--text)">${eq.assigned_to}</strong></div>` : ''}
            <a href="${eq.url}" class="btn btn-primary w-100 mt-3"><i class="bi bi-eye me-1"></i>Ver Ficha Completa</a>
        `;
        document.getElementById('resultCard').style.removeProperty('display');
    } else {
        document.getElementById('notFoundMsg').textContent = data.message || 'Equipo no encontrado';
        document.getElementById('notFoundCard').style.removeProperty('display');
    }
}

function showError(msg) {
    document.getElementById('notFoundMsg').textContent = msg;
    document.getElementById('notFoundCard').style.removeProperty('display');
}

// USB barcode reader support
let barcodeBuffer = '';
let barcodeTimer = null;
document.addEventListener('keypress', e => {
    if (document.activeElement.id === 'manualCode') return;
    if (e.key === 'Enter' && barcodeBuffer) {
        lookupCode(barcodeBuffer);
        barcodeBuffer = '';
    } else if (e.key.length === 1) {
        barcodeBuffer += e.key;
        clearTimeout(barcodeTimer);
        barcodeTimer = setTimeout(() => barcodeBuffer = '', 100);
    }
});
</script>
@endpush
@endsection
