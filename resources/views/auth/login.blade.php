<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activos Tecnológicos — Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --teal:        #14b8a6;
            --teal-dark:   #0d9488;
            --orange:      #f97316;
            --orange-dark: #ea580c;
            --bg-dark:  #0b1f1d;
            --bg-card:  #122420;
            --bg-input: #1a3330;
            --border:   #1f3f3a;
            --text:        #f0fdf9;
            --text-muted:  #7ab8b2;
        }
        body {
            background: var(--bg-dark);
            background-image: radial-gradient(ellipse at 20% 50%, rgba(20,184,166,.08) 0%, transparent 60%),
                              radial-gradient(ellipse at 80% 20%, rgba(249,115,22,.06) 0%, transparent 50%);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }
        .login-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 2.5rem;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 30px 80px rgba(0,0,0,.5), 0 0 0 1px rgba(20,184,166,.1);
        }
        .logo-icon {
            width: 68px; height: 68px;
            background: linear-gradient(135deg, var(--teal), var(--orange));
            border-radius: 18px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.9rem; margin: 0 auto 1.5rem;
            box-shadow: 0 8px 30px rgba(20,184,166,.35);
        }
        .form-control {
            background: var(--bg-input);
            border: 1px solid var(--border);
            color: var(--text);
            border-radius: 10px;
            padding: 0.75rem 1rem;
        }
        .form-control:focus {
            background: var(--bg-input);
            border-color: var(--teal);
            color: var(--text);
            box-shadow: 0 0 0 3px rgba(20,184,166,.2);
        }
        .form-control::placeholder { color: var(--text-muted); }
        .btn-login {
            background: linear-gradient(135deg, var(--teal), var(--teal-dark));
            border: none;
            border-radius: 10px;
            padding: 0.75rem;
            font-weight: 700;
            letter-spacing: .5px;
            color: #fff;
            transition: transform .15s, box-shadow .15s;
        }
        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 30px rgba(20,184,166,.4);
            color: #fff;
        }
        .form-label { color: var(--text-muted); font-size: .85rem; font-weight: 500; }
        .form-check-input { background-color: var(--bg-input); border-color: var(--border); }
        .form-check-label { color: var(--text-muted); font-size: .85rem; }
        .divider { height: 1px; background: var(--border); margin: 1.5rem 0; }
        .badge-teal { background: rgba(20,184,166,.15); color: var(--teal); border-radius: 20px; padding: 2px 10px; font-size: .78rem; }
    </style>
</head>
<body>
<div class="login-card">
    <div class="logo-icon"><i class="bi bi-laptop text-white"></i></div>
    <h4 class="text-center fw-bold mb-1" style="color:#fff">Activos Tecnológicos</h4>
    <p class="text-center mb-1" style="color:var(--text-muted);font-size:.88rem">Control de Activos y Equipos</p>
    <div class="text-center mb-4">
        <span class="badge-teal"><i class="bi bi-shield-check me-1"></i>Acceso Seguro</span>
    </div>

    @if($errors->any())
        <div class="alert border-0 rounded-3 mb-3 d-flex align-items-center gap-2" style="background:rgba(239,68,68,.12);border-left:3px solid #ef4444!important;color:#fca5a5;font-size:.88rem">
            <i class="bi bi-exclamation-triangle-fill"></i>{{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Correo electrónico</label>
            <input type="email" name="email" class="form-control" placeholder="admin@inventario.com"
                   value="{{ old('email') }}" required autofocus>
        </div>
        <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember">Recordarme</label>
            </div>
        </div>
        <button type="submit" class="btn btn-login w-100">
            <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
        </button>
    </form>
    <div class="divider"></div>
    <p class="text-center mb-0" style="color:var(--text-muted);font-size:.78rem">
        © {{ date('Y') }} Activos Tecnológicos
    </p>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
