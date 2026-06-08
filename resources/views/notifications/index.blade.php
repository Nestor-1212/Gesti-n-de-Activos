@extends('layouts.app')
@section('title','Notificaciones')
@section('page-title','Notificaciones')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0 fw-bold">Notificaciones</h5>
    <form method="POST" action="{{ route('notifications.read-all') }}">
        @csrf
        <button type="submit" class="btn btn-sm" style="background:var(--bg-card);border:1px solid var(--border)">
            <i class="bi bi-check-all me-1"></i>Marcar todas como leídas
        </button>
    </form>
</div>
<div class="card">
    @forelse($notifications as $n)
    <div class="d-flex align-items-start gap-3 p-3" style="border-bottom:1px solid var(--border);{{ $n->read_at ? 'opacity:.7' : '' }}">
        @php $colors = ['warranty'=>'#f97316','return'=>'#14b8a6','maintenance'=>'#2dd4bf','inactive'=>'#ef4444','general'=>'#8892a4']; $color = $colors[$n->type] ?? '#8892a4'; @endphp
        <div style="width:40px;height:40px;border-radius:10px;background:{{ $color }}22;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <i class="bi bi-bell" style="color:{{ $color }}"></i>
        </div>
        <div class="flex-fill">
            <div class="fw-600" style="font-size:.9rem">{{ $n->title }}</div>
            <div style="font-size:.83rem;color:var(--text-muted)">{{ $n->message }}</div>
            <div style="font-size:.75rem;color:var(--text-muted);margin-top:.25rem">{{ $n->created_at->diffForHumans() }}</div>
        </div>
        <div class="d-flex gap-1 flex-shrink-0">
            @if(!$n->read_at)
            <form method="POST" action="{{ route('notifications.read', $n) }}">
                @csrf
                <button type="submit" class="btn btn-sm" style="background:rgba(34,197,94,.15);color:#22c55e;border:none;padding:.25rem .5rem" title="Marcar leída"><i class="bi bi-check"></i></button>
            </form>
            @endif
            <form method="POST" action="{{ route('notifications.destroy', $n) }}">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm" style="background:rgba(239,68,68,.15);color:#ef4444;border:none;padding:.25rem .5rem" title="Eliminar"><i class="bi bi-x"></i></button>
            </form>
        </div>
    </div>
    @empty
    <div class="text-center py-5" style="color:var(--text-muted)">
        <i class="bi bi-bell-slash fs-2 d-block mb-2"></i>Sin notificaciones
    </div>
    @endforelse
    @if($notifications->hasPages())<div class="p-3">{{ $notifications->links() }}</div>@endif
</div>
@endsection
