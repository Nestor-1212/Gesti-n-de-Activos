@extends('layouts.app')
@section('title', $user->name)
@section('page-title', 'Perfil de Usuario')
@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('users.index') }}" class="btn btn-sm" style="background:var(--bg-card);border:1px solid var(--border)"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">{{ $user->name }}</h5>
</div>
<div class="card"><div class="card-body">
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Rol:</strong> {{ $user->getRoleNames()->implode(', ') }}</p>
    <p><strong>Estado:</strong> {{ $user->active ? 'Activo' : 'Inactivo' }}</p>
</div></div>
@endsection
