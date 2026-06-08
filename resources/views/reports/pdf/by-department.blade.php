<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Equipos por Departamento</title>
<style>body{font-family:Arial,sans-serif;font-size:10px;color:#1a1a2e}h2{text-align:center;font-size:14px}.dh{background:#1a1a2e;color:white;padding:5px 8px;font-weight:bold;margin-top:12px}.ch{background:#e8e8e8;padding:4px 8px;font-style:italic;margin-top:6px}table{width:100%;border-collapse:collapse;margin-top:3px}th{background:#ddd;padding:4px 7px;font-size:9px;text-align:left}td{border:1px solid #eee;padding:4px 7px}</style>
</head>
<body>
<h2>EQUIPOS POR DEPARTAMENTO</h2>
<p style="text-align:center;font-size:10px;color:#555">Generado el {{ now()->format('d/m/Y H:i') }}</p>
@foreach($departments as $dept)
@php $total = $dept->collaborators->sum(fn($c)=>$c->assignments->count()); @endphp
@if($total > 0)
<div class="dh">{{ $dept->name }} — {{ $total }} equipo(s)</div>
@foreach($dept->collaborators as $c)
@if($c->assignments->isNotEmpty())
<div class="ch">{{ $c->full_name }} ({{ $c->position }})</div>
<table><thead><tr><th>Equipo</th><th>Serie</th><th>Fecha Entrega</th></tr></thead>
<tbody>
@foreach($c->assignments as $a)
<tr><td>{{ $a->equipment->brand }} {{ $a->equipment->model }}</td><td>{{ $a->equipment->serial_number }}</td><td>{{ $a->assigned_at->format('d/m/Y') }}</td></tr>
@endforeach
</tbody></table>
@endif
@endforeach
@endif
@endforeach
</body>
</html>
