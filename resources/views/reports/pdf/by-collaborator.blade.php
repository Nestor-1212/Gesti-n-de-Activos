<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Equipos por Colaborador</title>
<style>
body { font-family: Arial, sans-serif; font-size: 10px; color: #1a1a2e; }
h2 { text-align: center; font-size: 14px; }
p.subtitle { text-align: center; font-size: 10px; color: #555; }
.section { margin-top: 15px; }
.section-header { background: #1a1a2e; color: white; padding: 5px 8px; font-weight: bold; font-size: 10px; }
table { width: 100%; border-collapse: collapse; }
th { background: #e0e0e0; padding: 4px 7px; font-size: 9px; text-align: left; }
td { border: 1px solid #ddd; padding: 4px 7px; }
</style>
</head>
<body>
<h2>EQUIPOS POR COLABORADOR</h2>
<p class="subtitle">Generado el {{ now()->format('d/m/Y H:i') }}</p>
@foreach($collaborators as $c)
@if($c->assignments->isNotEmpty())
<div class="section">
    <div class="section-header">{{ $c->full_name }} — {{ $c->cedula }} | {{ $c->department?->name }} | {{ $c->position }}</div>
    <table><thead><tr><th>Equipo</th><th>Serie</th><th>Tipo</th><th>Fecha Entrega</th><th>Devolución Est.</th></tr></thead>
    <tbody>
    @foreach($c->assignments as $a)
    <tr><td>{{ $a->equipment->brand }} {{ $a->equipment->model }}</td><td>{{ $a->equipment->serial_number }}</td><td>{{ $a->equipment->type_label }}</td><td>{{ $a->assigned_at->format('d/m/Y') }}</td><td>{{ $a->expected_return?->format('d/m/Y') ?? '—' }}</td></tr>
    @endforeach
    </tbody></table>
</div>
@endif
@endforeach
</body>
</html>
