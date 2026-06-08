<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Acta de Entrega #{{ $assignment->id }}</title>
<style>
body { font-family: Arial, sans-serif; font-size: 12px; color: #1a1a2e; margin: 20px; }
h2 { text-align: center; font-size: 16px; margin-bottom: 5px; }
.subtitle { text-align: center; font-size: 11px; color: #555; margin-bottom: 20px; }
table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
td, th { border: 1px solid #ccc; padding: 7px 10px; }
th { background: #f0f0f0; font-weight: bold; text-align: left; }
.section-title { background: #1a1a2e; color: white; padding: 6px 10px; font-weight: bold; font-size: 11px; margin-top: 15px; margin-bottom: 0; }
.signature-area { margin-top: 40px; }
.signature-line { border-top: 1px solid #1a1a2e; width: 45%; display: inline-block; text-align: center; padding-top: 5px; font-size: 11px; }
</style>
</head>
<body>
<h2>ACTA DE ENTREGA DE EQUIPO TECNOLÓGICO</h2>
<p class="subtitle">Asignación #{{ $assignment->id }} — Generado el {{ now()->format('d/m/Y') }}</p>

<div class="section-title">INFORMACIÓN DEL EQUIPO</div>
<table>
<tr><th>Marca / Modelo</th><td>{{ $assignment->equipment->brand }} {{ $assignment->equipment->model }}</td><th>Tipo</th><td>{{ $assignment->equipment->type_label }}</td></tr>
<tr><th>Número de Serie</th><td>{{ $assignment->equipment->serial_number }}</td><th>IMEI</th><td>{{ $assignment->equipment->imei1 ?? '—' }}</td></tr>
<tr><th>S.O.</th><td>{{ $assignment->equipment->operating_system ?? '—' }}</td><th>Código</th><td>{{ $assignment->equipment->barcode }}</td></tr>
</table>

<div class="section-title">INFORMACIÓN DEL COLABORADOR</div>
<table>
<tr><th>Nombre</th><td>{{ $assignment->collaborator->full_name }}</td><th>Cédula</th><td>{{ $assignment->collaborator->cedula }}</td></tr>
<tr><th>Cargo</th><td>{{ $assignment->collaborator->position ?? '—' }}</td><th>Departamento</th><td>{{ $assignment->collaborator->department?->name ?? '—' }}</td></tr>
<tr><th>Correo</th><td>{{ $assignment->collaborator->email ?? '—' }}</td><th>Teléfono</th><td>{{ $assignment->collaborator->phone ?? '—' }}</td></tr>
</table>

<div class="section-title">DATOS DE LA ASIGNACIÓN</div>
<table>
<tr><th>Fecha de Entrega</th><td>{{ $assignment->assigned_at->format('d/m/Y') }}</td><th>Devolución Estimada</th><td>{{ $assignment->expected_return?->format('d/m/Y') ?? '—' }}</td></tr>
<tr><th>Motivo</th><td colspan="3">{{ $assignment->reason ?? '—' }}</td></tr>
<tr><th>Observaciones</th><td colspan="3">{{ $assignment->observations ?? '—' }}</td></tr>
<tr><th>Asignado por</th><td colspan="3">{{ $assignment->assignedBy->name }}</td></tr>
</table>

<p style="font-size:11px;margin-top:15px">El colaborador declara haber recibido el equipo descrito en buen estado y se compromete a devolverlo en las mismas condiciones, siendo responsable por cualquier daño o pérdida del mismo durante el período de asignación.</p>

<div class="signature-area" style="display:flex;justify-content:space-between;margin-top:50px">
    <div style="text-align:center;width:45%">
        <div style="border-top:1px solid #1a1a2e;padding-top:5px;font-size:11px">
            <strong>ENTREGADO POR</strong><br>{{ $assignment->assignedBy->name }}
        </div>
    </div>
    <div style="text-align:center;width:45%">
        <div style="border-top:1px solid #1a1a2e;padding-top:5px;font-size:11px">
            <strong>RECIBIDO POR</strong><br>{{ $assignment->collaborator->full_name }}<br>Cédula: {{ $assignment->collaborator->cedula }}
        </div>
    </div>
</div>
</body>
</html>
