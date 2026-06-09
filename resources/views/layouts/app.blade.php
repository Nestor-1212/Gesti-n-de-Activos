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

        /* ── UTILITIES ───────────────────────────────────────── */
        .fw-600 { font-weight: 600; }

        /* ── SIDEBAR ─────────────────────────────────────────── */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--bg-sidebar);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 1040;
            transition: transform .28s cubic-bezier(.4,0,.2,1);
            overflow-y: auto;
        }
        .sidebar-logo {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; gap: .75rem;
            flex-shrink: 0;
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
            transition: background .15s, color .15s;
        }
        .nav-link:hover { background: var(--bg-hover); color: var(--text); }
        .nav-link.active {
            background: rgba(20,184,166,.15);
            color: var(--teal-light);
            font-weight: 600;
            border-left: 3px solid var(--teal);
        }
        .nav-link i { font-size: 1rem; width: 20px; text-align: center; }

        /* ── SIDEBAR BACKDROP (mobile) ───────────────────────── */
        .sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.55);
            z-index: 1039;
            backdrop-filter: blur(2px);
            opacity: 0;
            transition: opacity .28s;
        }
        .sidebar-backdrop.show { opacity: 1; }

        /* ── MAIN ────────────────────────────────────────────── */
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

        /* ── CARDS ───────────────────────────────────────────── */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 14px;
            transition: border-color .2s;
        }
        .card:hover { border-color: rgba(20,184,166,.25); }
        .card-header {
            background: transparent;
            border-bottom: 1px solid var(--border);
            padding: 1rem 1.25rem;
            font-weight: 600;
        }

        /* ── STAT CARDS ──────────────────────────────────────── */
        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 1.25rem;
            transition: transform .2s, box-shadow .2s, border-color .2s;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(0,0,0,.3); border-color: rgba(20,184,166,.25); }
        .stat-icon {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
        }
        .stat-value { font-size: 1.8rem; font-weight: 700; line-height: 1; }
        .stat-label { font-size: .8rem; color: var(--text-muted); margin-top: .25rem; }

        /* ── BADGES ──────────────────────────────────────────── */
        .badge-available   { background: rgba(20,184,166,.18);  color: var(--teal-light); }
        .badge-assigned    { background: rgba(249,115,22,.18);  color: #fb923c; }
        .badge-maintenance { background: rgba(249,115,22,.15);  color: var(--orange); }
        .badge-damaged     { background: rgba(239,68,68,.15);   color: var(--danger); }
        .badge-lost        { background: rgba(239,68,68,.12);   color: #fca5a5; }
        .badge-retired     { background: rgba(122,184,178,.12); color: var(--text-muted); }
        .badge-active      { background: rgba(20,184,166,.18);  color: var(--teal-light); }
        .badge-returned    { background: rgba(122,184,178,.12); color: var(--text-muted); }

        /* ── TABLE ───────────────────────────────────────────── */
        .table { color: var(--text); }
        .table th { color: var(--text-muted); font-size: .78rem; text-transform: uppercase; letter-spacing: .5px; font-weight: 600; border-color: var(--border); }
        .table td { border-color: var(--border); vertical-align: middle; font-size: .9rem; }
        .table tbody tr:hover { background: rgba(255,255,255,.03); }

        /* ── FORMS ───────────────────────────────────────────── */
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
        .btn-orange { background: var(--orange); border-color: var(--orange); color: #fff; font-weight: 600; }
        .btn-orange:hover { background: var(--orange-dark); border-color: var(--orange-dark); color: #fff; }

        /* ── NOTIFICATION BADGE ──────────────────────────────── */
        .notif-badge {
            position: absolute; top: -4px; right: -4px;
            background: var(--orange); color: #fff;
            font-size: .65rem; font-weight: 700;
            border-radius: 50%; width: 18px; height: 18px;
            display: flex; align-items: center; justify-content: center;
        }

        /* ── PAGINATION ──────────────────────────────────────── */
        .pagination .page-link { background: var(--bg-card); border-color: var(--border); color: var(--text-muted); }
        .pagination .page-link:hover { background: var(--bg-hover); color: var(--teal-light); }
        .pagination .active .page-link { background: var(--teal); border-color: var(--teal); color: #fff; }

        /* ── SCROLLBAR ───────────────────────────────────────── */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg-sidebar); }
        ::-webkit-scrollbar-thumb { background: rgba(20,184,166,.3); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--teal); }

        /* ── TOAST STACK ─────────────────────────────────────── */
        .toast-stack {
            position: fixed; top: 1.25rem; right: 1.25rem;
            z-index: 9999; width: 340px;
            display: flex; flex-direction: column; gap: .5rem;
        }
        .app-toast {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: .85rem 1rem;
            display: flex; align-items: flex-start; gap: .75rem;
            box-shadow: 0 8px 32px rgba(0,0,0,.4);
            animation: toast-in .3s cubic-bezier(.34,1.56,.64,1);
        }
        .app-toast.toast-success { border-left: 3px solid var(--success); }
        .app-toast.toast-error   { border-left: 3px solid var(--danger); }
        .app-toast.toast-warning { border-left: 3px solid var(--orange); }
        .app-toast .toast-icon { font-size: 1.15rem; flex-shrink: 0; margin-top: .05rem; }
        .app-toast .toast-body { flex: 1; font-size: .88rem; line-height: 1.4; }
        .app-toast .toast-close { background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 0; font-size: 1rem; flex-shrink: 0; }
        .app-toast .toast-close:hover { color: var(--text); }
        @keyframes toast-in {
            from { opacity: 0; transform: translateX(24px) scale(.96); }
            to   { opacity: 1; transform: translateX(0) scale(1); }
        }
        .app-toast.toast-out {
            animation: toast-out .2s ease forwards;
        }
        @keyframes toast-out {
            to { opacity: 0; transform: translateX(24px) scale(.96); }
        }

        /* ── CONFIRM MODAL ───────────────────────────────────── */
        #confirmModal .modal-content {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 16px;
        }
        #confirmModal .modal-header { border-color: var(--border); }
        #confirmModal .modal-footer { border-color: var(--border); }
        .btn-confirm-danger {
            background: var(--danger); border-color: var(--danger);
            color: #fff; font-weight: 600;
        }
        .btn-confirm-danger:hover { background: #dc2626; border-color: #dc2626; color: #fff; }

        /* ── RESPONSIVE ──────────────────────────────────────── */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-wrapper { margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>

<!-- SIDEBAR BACKDROP -->
<div class="sidebar-backdrop" id="sidebarBackdrop" onclick="closeSidebar()"></div>

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

    <div style="padding:.75rem 1rem 1.25rem; border-top:1px solid var(--border); flex-shrink:0;">
        <div class="d-flex align-items-center gap-2" style="font-size:.82rem;">
            <div style="width:34px;height:34px;background:linear-gradient(135deg,var(--teal),var(--orange));border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;flex-shrink:0;color:#fff;">
                {{ strtoupper(substr(auth()->user()->name,0,1)) }}
            </div>
            <div style="overflow:hidden;">
                <div class="fw-600" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ auth()->user()->name }}</div>
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
            <button class="btn btn-sm d-md-none" onclick="toggleSidebar()" style="background:var(--bg-hover);border:1px solid var(--border);width:36px;height:36px;padding:0;border-radius:8px;">
                <i class="bi bi-list"></i>
            </button>
            <span class="topbar-title">@yield('page-title', 'Panel')</span>
        </div>
        <div class="topbar-actions">
            @php
                $unreadCount = \App\Models\Notification::where('user_id', auth()->id())->whereNull('read_at')->count();
            @endphp
            <a href="{{ route('notifications.index') }}" class="btn position-relative" style="background:var(--bg-hover);border:1px solid var(--border);width:38px;height:38px;padding:0;display:flex;align-items:center;justify-content:center;border-radius:10px;" title="Notificaciones">
                <i class="bi bi-bell"></i>
                @if($unreadCount > 0)
                    <span class="notif-badge">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                @endif
            </a>
            <a href="{{ route('equipment.create') }}" class="btn btn-sm" style="border-radius:10px;padding:.4rem .9rem;background:linear-gradient(135deg,var(--teal),var(--teal-dark));color:#fff;font-weight:600;border:none;box-shadow:0 4px 15px rgba(20,184,166,.3);">
                <i class="bi bi-plus-lg me-1"></i>Nuevo Equipo
            </a>
        </div>
    </div>

    <div class="content">
        @yield('content')
    </div>
</div>

<!-- ── TOAST STACK ──────────────────────────────────────────────── -->
<div class="toast-stack" id="toastStack"></div>

<!-- ── CONFIRM MODAL ────────────────────────────────────────────── -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <div class="d-flex align-items-center gap-2">
                    <div id="confirmIcon" style="width:40px;height:40px;border-radius:10px;background:rgba(239,68,68,.15);display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-exclamation-triangle-fill" style="color:var(--danger)"></i>
                    </div>
                    <h6 class="modal-title fw-bold mb-0" id="confirmTitle">Confirmar acción</h6>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-2">
                <p id="confirmMessage" class="mb-0" style="color:var(--text-muted);font-size:.9rem">¿Estás seguro de que deseas realizar esta acción? Esta operación no se puede deshacer.</p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn" data-bs-dismiss="modal" style="background:var(--bg-hover);border:1px solid var(--border)">Cancelar</button>
                <button type="button" id="confirmBtn" class="btn btn-confirm-danger">
                    <i class="bi bi-trash me-1"></i>Eliminar
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// ── Sidebar (mobile) ─────────────────────────────────────────────
function toggleSidebar() {
    const sidebar   = document.getElementById('sidebar');
    const backdrop  = document.getElementById('sidebarBackdrop');
    const isOpen    = sidebar.classList.contains('open');
    if (isOpen) {
        closeSidebar();
    } else {
        sidebar.classList.add('open');
        backdrop.style.display = 'block';
        requestAnimationFrame(() => backdrop.classList.add('show'));
    }
}

function closeSidebar() {
    const sidebar  = document.getElementById('sidebar');
    const backdrop = document.getElementById('sidebarBackdrop');
    sidebar.classList.remove('open');
    backdrop.classList.remove('show');
    setTimeout(() => backdrop.style.display = 'none', 280);
}

// ── Toast system ─────────────────────────────────────────────────
function showToast(message, type = 'success', duration = 4500) {
    const icons = { success: 'bi-check-circle-fill', error: 'bi-x-circle-fill', warning: 'bi-exclamation-triangle-fill' };
    const colors = { success: 'var(--success)', error: 'var(--danger)', warning: 'var(--orange)' };
    const stack = document.getElementById('toastStack');

    const toast = document.createElement('div');
    toast.className = `app-toast toast-${type}`;
    toast.innerHTML = `
        <i class="bi ${icons[type]} toast-icon" style="color:${colors[type]}"></i>
        <div class="toast-body">${message}</div>
        <button class="toast-close" onclick="dismissToast(this.closest('.app-toast'))"><i class="bi bi-x"></i></button>
    `;
    stack.appendChild(toast);

    setTimeout(() => dismissToast(toast), duration);
}

function dismissToast(el) {
    if (!el || el.classList.contains('toast-out')) return;
    el.classList.add('toast-out');
    setTimeout(() => el.remove(), 200);
}

// ── Confirm modal ─────────────────────────────────────────────────
let _pendingForm = null;
const _confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));

function confirmDelete(formEl, title = '¿Eliminar registro?', message = 'Esta acción es permanente y no se puede deshacer.') {
    _pendingForm = formEl;
    document.getElementById('confirmTitle').textContent   = title;
    document.getElementById('confirmMessage').textContent = message;
    _confirmModal.show();
}

document.getElementById('confirmBtn').addEventListener('click', function () {
    if (_pendingForm) {
        _confirmModal.hide();
        _pendingForm.submit();
        _pendingForm = null;
    }
});

// ── Flash toasts (rendered on load) ──────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    @if(session('success'))
        showToast(@json(session('success')), 'success');
    @endif
    @if(session('error'))
        showToast(@json(session('error')), 'error');
    @endif
    @if(session('warning'))
        showToast(@json(session('warning')), 'warning');
    @endif
    @if($errors->any())
        showToast(@json($errors->first()), 'error', 7000);
    @endif
});
</script>
@stack('scripts')
</body>
</html>
