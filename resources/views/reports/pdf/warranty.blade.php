<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Garantías por Vencer</title>
<style>body{font-family:Arial,sans-serif;font-size:10px;color:#1a1a2e}h2{text-align:center;font-size:14px}table{width:100%;border-collapse:collapse;margin-top:10px}th{background:#1a1a2e;color:white;padding:5px 7px;text-align:left}td{border:1px solid #ddd;padding:5px 7px}tr:nth-child(even)td{background:#f9f9f9}</style>
</head>
<body>
<h2>GARANTÍAS POR VENCER — PRÓXIMOS {{ $days }} DÍAS</h2>
<p style="text-align:center;font-size:10px;color:#555">Generado el {{ now()->format('d/m/Y H:i') }}</p>
<table>
<thead><tr><th>Equipo</th><th>Serie</th><th>Proveedor</th><th>Vencimiento</th><th>Días Restantes</th><th>Asignado A</th></tr></thead>
<tbody>
@foreach($equipment as $eq)
@php $dias = now()->diffInDays($eq->warranty_expiry); @endphp
<tr><td>{{ $eq->brand }} {{ $eq->model }}</td><td>{{ $eq->serial_number }}</td><td>{{ $eq->supplier ?? '—' }}</td><td>{{ $eq->warranty_expiry->format('d/m/Y') }}</td><td>{{ $dias }} días</td><td>{{ $eq->activeAssignment?->collaborator?->full_name ?? '—' }}</td></tr>
@endforeach
</tbody>
</table>
</body>
</html>
