@extends('layouts.app')
@section('title','Bitácora de Actividades')
@section('page-title','Bitácora de Actividades')
@section('content')
<h5 class="fw-bold mb-4">Bitácora de Actividades</h5>
<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-6 col-md-3">
                <select name="action" class="form-select">
                    <option value="">Todas las acciones</option>
                    @foreach(['login','logout','create','update','delete','assign','return','maintenance'] as $a)
                        <option value="{{ $a }}" {{ request('action')===$a?'selected':'' }}>{{ ucfirst($a) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-3">
                <select name="user_id" class="form-select">
                    <option value="">Todos los usuarios</option>
                    @foreach($users as $u)<option value="{{ $u->id }}" {{ request('user_id')==$u->id?'selected':'' }}>{{ $u->name }}</option>@endforeach
                </select>
            </div>
            <div class="col-6 col-md-2"><input type="date" name="from" class="form-control" value="{{ request('from') }}"></div>
            <div class="col-6 col-md-2"><input type="date" name="to" class="form-control" value="{{ request('to') }}"></div>
            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill">Filtrar</button>
                <a href="{{ route('activity-logs.index') }}" class="btn" style="background:var(--bg-hover);border:1px solid var(--border)"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table mb-0" style="font-size:.85rem">
            <thead><tr><th>Acción</th><th>Descripción</th><th>Usuario</th><th>IP</th><th>Fecha</th></tr></thead>
            <tbody>
            @forelse($logs as $log)
            @php $actionColors=['login'=>'#22c55e','logout'=>'#8892a4','create'=>'#14b8a6','update'=>'#f97316','delete'=>'#ef4444','assign'=>'#2dd4bf','return'=>'#14b8a6','maintenance'=>'#f97316']; $color = $actionColors[$log->action] ?? '#8892a4'; @endphp
            <tr>
                <td><span class="badge rounded-pill" style="background:{{ $color }}22;color:{{ $color }}">{{ $log->action }}</span></td>
                <td>{{ $log->description }}</td>
                <td>{{ $log->user?->name ?? 'Sistema' }}</td>
                <td style="color:var(--text-muted)">{{ $log->ip_address ?? '—' }}</td>
                <td style="color:var(--text-muted)">{{ $log->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center py-5" style="color:var(--text-muted)"><i class="bi bi-journal-x fs-2 d-block mb-2"></i>Sin registros de actividad.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())<div class="card-body pt-0">{{ $logs->links() }}</div>@endif
</div>
@endsection
