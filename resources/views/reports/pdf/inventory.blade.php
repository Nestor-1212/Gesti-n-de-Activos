<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Inventario General</title>
<style>
body { font-family: Arial, sans-serif; font-size: 10px; color: #1a1a2e; }
h2 { text-align: center; font-size: 14px; }
p.subtitle { text-align: center; font-size: 10px; color: #555; margin-bottom: 15px; }
table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
th { background: #1a1a2e; color: white; padding: 5px 7px; font-size: 9px; text-align: left; }
td { border: 1px solid #ddd; padding: 5px 7px; }
tr:nth-child(even) td { background: #f9f9f9; }
.badge { padding: 2px 6px; border-radius: 10px; font-size: 9px; }
.available { background: #dcfce7; color: #16a34a; }
.assigned { background: #dbeafe; color: #1d4ed8; }
.maintenance { background: #fef9c3; color: #a16207; }
.damaged, .lost { background: #fee2e2; color: #dc2626; }
</style>
</head>
<body>
<h2>INVENTARIO GENERAL DE EQUIPOS TECNOLÓGICOS</h2>
<p class="subtitle">Generado el {{ now()->format('d/m/Y H:i') }} — Total: {{ $equipment->count() }} equipos</p>
<table>
<thead><tr><th>#</th><th>Marca / Modelo</th><th>Serie</th><th>Tipo</th><th>Estado</th><th>S.O.</th><th>Garantía</th><th>Costo</th><th>Asignado A</th></tr></thead>
<tbody>
@foreach($equipment as $i => $eq)
<tr>
    <td>{{ $i+1 }}</td>
    <td>{{ $eq->brand }} {{ $eq->model }}</td>
    <td>{{ $eq->serial_number }}</td>
    <td>{{ $eq->type_label }}</td>
    <td><span class="badge {{ in_array($eq->status,['available','assigned','maintenance','damaged','lost'])?$eq->status:'' }}">{{ $eq->status_label }}</span></td>
    <td>{{ $eq->operating_system ?? '—' }}</td>
    <td>{{ $eq->warranty_expiry?->format('d/m/Y') ?? '—' }}</td>
    <td>{{ $eq->cost ? '$'.number_format($eq->cost,2) : '—' }}</td>
    <td>{{ $eq->activeAssignment?->collaborator?->full_name ?? '—' }}</td>
</tr>
@endforeach
</tbody>
</table>
</body>
</html>
