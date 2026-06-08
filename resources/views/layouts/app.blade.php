<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Inventario Tecnológico')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-w: 260px;
            /* Paleta: verde agua + naranja + blanco */
            --teal:        #14b8a6;
            --teal-dark:   #0d9488;
            --teal-light:  #2dd4bf;
            --orange:      #f97316;
            --orange-dark: #ea580c;
            --bg-app:     #0b1f1d;
            --bg-sidebar: #0e2421;
            --bg-card:    #122420;
            --bg-hover:   #1a3330;
            --border:     #1f3f3a;
            --accent:        var(--teal);
            --accent-hover:  var(--teal-dark);
            --secondary:     var(--orange);
            --danger:  #ef4444;
            --success: #22c55e;
            --warning: var(--orange);
            --info:    var(--teal-light);
            --text:        #f0fdf9;
            --text-muted:  #7ab8b2;
            --white: #ffffff;
        }
        * { box-sizing: border-box; }
        body {
            background: var(--bg-app);
            background-image: radial-gradient(ellipse at 0% 100%, rgba(20,184,166,.06) 0%, transparent 50%);
            color: var(--text);
            font-family: 'Segoe UI', system-ui, sans-serif;
            margin: 0;
            display: flex;
            min-height: 100vh;
        }
        /* SIDEBAR */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--bg-sidebar);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 1000;
            transition: transform .3s;
            overflow-y: auto;
        }
        .sidebar-logo {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; gap: .75rem;
        }
        .sidebar-logo .logo-icon {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, var(--teal), var(--orange));
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; flex-shrink: 0;
            box-shadow: 0 4px 15px rgba(20,184,166,.3);
        }
        .sidebar-logo span { font-size: .92rem; font-weight: 700; line-height: 1.2; }
        .sidebar-logo small { font-size: .72rem; color: var(--text-muted); display: block; }
        .nav-section {
            padding: .75rem 1rem .25rem;
            font-size: .7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
            font-weight: 600;
        }
        .nav-link {
            display: flex; align-items: center; gap: .7rem;
            padding: .55rem 1rem;
            border-radius: 10px;
            margin: 1px .5rem;
            color: var(--text-muted);
            text-decoration: none;
            font-size: .88rem;
            transition: all .15s;
        }
        .nav-link:hover { background: var(--bg-hover); color: var(--text); }
        .nav-link.active {
            background: rgba(20,184,166,.15);
            color: var(--teal-light);
            font-weight: 600;
            border-left: 3px solid var(--teal);
        }
        .nav-link i { font-size: 1rem; width: 20px; text-align: center; }
        /* MAIN */
        .main-wrapper {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .topbar {
            background: var(--bg-sidebar);
            border-bottom: 1px solid var(--border);
            padding: .75rem 1.5rem;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 900;
        }
        .topbar-title { font-size: 1rem; font-weight: 600; color: var(--text); }
        .topbar-actions { display: flex; align-items: center; gap: .75rem; }
        .content { padding: 1.5rem; flex: 1; }
        /* CARDS */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 14px;
        }
        .card-header {
            background: transparent;
            border-bottom: 1px solid var(--border);
            padding: 1rem 1.25rem;
            font-weight: 600;
        }
        /* STAT CARDS */
        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 1.25rem;
            transition: transform .2s, box-shadow .2s;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(0,0,0,.3); }
        .stat-icon {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
        }
        .stat-value { font-size: 1.8rem; font-weight: 700; line-height: 1; }
        .stat-label { font-size: .8rem; color: var(--text-muted); margin-top: .25rem; }
        /* BADGE STATUS */
        .badge-available   { background: rgba(20,184,166,.18);  color: var(--teal-light); }
        .badge-assigned    { background: rgba(249,115,22,.18);  color: #fb923c; }
        .badge-maintenance { background: rgba(249,115,22,.15);  color: var(--orange); }
        .badge-damaged     { background: rgba(239,68,68,.15);   color: var(--danger); }
        .badge-lost        { background: rgba(239,68,68,.12);   color: #fca5a5; }
        .badge-retired     { background: rgba(122,184,178,.12); color: var(--text-muted); }
        .badge-active   { background: rgba(20,184,166,.18); color: var(--teal-light); }
        .badge-returned { background: rgba(122,184,178,.12); color: var(--text-muted); }
        /* TABLE */
        .table { color: var(--text); }
        .table th { color: var(--text-muted); font-size: .78rem; text-transform: uppercase; letter-spacing: .5px; font-weight: 600; border-color: var(--border); }
        .table td { border-color: var(--border); vertical-align: middle; font-size: .9rem; }
        .table tbody tr:hover { background: rgba(255,255,255,.03); }
        /* FORMS */
        .form-control, .form-select {
            background: var(--bg-hover);
            border: 1px solid var(--border);
            color: var(--text);
            border-radius: 8px;
        }
        .form-control:focus, .form-select:focus {
            background: var(--bg-hover);
            border-color: var(--teal);
            color: var(--text);
            box-shadow: 0 0 0 3px rgba(20,184,166,.2);
        }
        .form-control::placeholder { color: var(--text-muted); }
        .form-label { font-size: .85rem; font-weight: 500; color: var(--text-muted); margin-bottom: .35rem; }
        .input-group-text { background: var(--bg-hover); border-color: var(--border); color: var(--text-muted); }
        .btn-primary { background: var(--teal); border-color: var(--teal); color: #fff; font-weight: 600; }
        .btn-primary:hover { background: var(--teal-dark); border-color: var(--teal-dark); color: #fff; box-shadow: 0 4px 15px rgba(20,184,166,.35); }
        /* Botón naranja (secondary) */
        .btn-orange { background: var(--orange); border-color: var(--orange); color: #fff; font-weight: 600; }
        .btn-orange:hover { background: var(--orange-dark); border-color: var(--orange-dark); color: #fff; }
        /* NOTIFICATIONS */
        .notif-badge {
            position: absolute; top: -4px; right: -4px;
            background: var(--orange); color: #fff;
            font-size: .65rem; font-weight: 700;
            border-radius: 50%; width: 18px; height: 18px;
            display: flex; align-items: center; justify-content: center;
        }
        /* PAGINATION */
        .pagination .page-link {
            background: var(--bg-card); border-color: var(--border); color: var(--text-muted);
        }
        .pagination .page-link:hover { background: var(--bg-hover); color: var(--teal-light); }
        .pagination .active .page-link { background: var(--teal); border-color: var(--teal); color: #fff; }
        /* ALERTS */
        .alert-success { background: rgba(20,184,166,.1);  border-color: rgba(20,184,166,.3);  color: var(--teal-light); }
        .alert-danger   { background: rgba(239,68,68,.1);   border-color: rgba(239,68,68,.3);   color: #fca5a5; }
        .alert-warning  { background: rgba(249,115,22,.1);  border-color: rgba(249,115,22,.3);  color: #fdba74; }
        /* CARD hover accent top border */
        .card:hover { border-color: rgba(20,184,166,.25); transition: border-color .2s; }
        /* SCROLLBAR */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg-sidebar); }
        ::-webkit-scrollbar-thumb { background: rgba(20,184,166,.3); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--teal); }
        /* RESPONSIVE */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-wrapper { margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <div class="logo-icon"><i class="bi bi-laptop text-white"></i></div>
        <div>
            <span>Inventario TI</span>
            <small>Control de Activos</small>
        </div>
    </div>

    <div style="padding:.75rem 0 1rem; flex:1;">
        <div class="nav-section">Principal</div>
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill"></i> Dashboard
        </a>
        <a href="{{ route('scanner.index') }}" class="nav-link {{ request()->routeIs('scanner.*') ? 'active' : '' }}">
            <i class="bi bi-qr-code-scan"></i> Escáner
        </a>

        <div class="nav-section">Inventario</div>
        <a href="{{ route('equipment.index') }}" class="nav-link {{ request()->routeIs('equipment.*') ? 'active' : '' }}">
            <i class="bi bi-phone"></i> Equipos
        </a>
        <a href="{{ route('assignments.index') }}" class="nav-link {{ request()->routeIs('assignments.*') ? 'active' : '' }}">
            <i class="bi bi-arrow-left-right"></i> Asignaciones
        </a>
        <a href="{{ route('maintenance.index') }}" class="nav-link {{ request()->routeIs('maintenance.*') ? 'active' : '' }}">
            <i class="bi bi-tools"></i> Mantenimiento
        </a>

        <div class="nav-section">Colaboradores</div>
        <a href="{{ route('collaborators.index') }}" class="nav-link {{ request()->routeIs('collaborators.*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i> Colaboradores
        </a>
        <a href="{{ route('departments.index') }}" class="nav-link {{ request()->routeIs('departments.*') ? 'active' : '' }}">
            <i class="bi bi-building"></i> Departamentos
        </a>

        <div class="nav-section">Análisis</div>
        <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-fill"></i> Reportes
        </a>
        <a href="{{ route('activity-logs.index') }}" class="nav-link {{ request()->routeIs('activity-logs.*') ? 'active' : '' }}">
            <i class="bi bi-journal-text"></i> Bitácora
        </a>

        @if(auth()->user()->hasRole(['super-admin','admin']))
        <div class="nav-section">Administración</div>
        <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i class="bi bi-person-lock"></i> Usuarios
        </a>
        @endif
    </div>

    <div style="padding:.75rem 1rem 1.25rem; border-top:1px solid var(--border);">
        <div class="d-flex align-items-center gap-2" style="font-size:.82rem;">
            <div style="width:34px;height:34px;background:linear-gradient(135deg,var(--teal),var(--orange));border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;flex-shrink:0;color:#fff;">
                {{ strtoupper(substr(auth()->user()->name,0,1)) }}
            </div>
            <div style="overflow:hidden;">
                <div style="font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ auth()->user()->name }}</div>
                <div style="color:var(--text-muted);font-size:.75rem;">{{ auth()->user()->getRoleNames()->first() }}</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="mt-2">
            @csrf
            <button type="submit" class="btn w-100 text-start nav-link" style="border:none;padding:.55rem 1rem;">
                <i class="bi bi-box-arrow-left"></i> Cerrar Sesión
            </button>
        </form>
    </div>
</aside>

<!-- MAIN -->
<div class="main-wrapper">
    <div class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm d-md-none" onclick="toggleSidebar()" style="background:var(--bg-hover);border:1px solid var(--border);">
                <i class="bi bi-list"></i>
            </button>
            <span class="topbar-title">@yield('page-title', 'Panel')</span>
        </div>
        <div class="topbar-actions">
            @php
                $unreadCount = \App\Models\Notification::where('user_id', auth()->id())->whereNull('read_at')->count();
            @endphp
            <a href="{{ route('notifications.index') }}" class="btn position-relative" style="background:var(--bg-hover);border:1px solid var(--border);width:38px;height:38px;padding:0;display:flex;align-items:center;justify-content:center;border-radius:10px;">
                <i class="bi bi-bell"></i>
                @if($unreadCount > 0)
                    <span class="notif-badge">{{ $unreadCount }}</span>
                @endif
            </a>
            <a href="{{ route('equipment.create') }}" class="btn btn-sm" style="border-radius:10px;padding:.4rem .9rem;background:linear-gradient(135deg,var(--teal),var(--teal-dark));color:#fff;font-weight:600;border:none;box-shadow:0 4px 15px rgba(20,184,166,.3);">
                <i class="bi bi-plus-lg me-1"></i>Nuevo Equipo
            </a>
        </div>
    </div>

    <div class="content">
        @if(session('success'))
            <div class="alert alert-success border-0 rounded-3 mb-3 d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger border-0 rounded-3 mb-3 d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger border-0 rounded-3 mb-3">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <ul class="mb-0 mt-1 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
}
</script>
@stack('scripts')
</body>
</html>
