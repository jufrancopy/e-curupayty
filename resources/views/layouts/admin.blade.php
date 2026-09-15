<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel de Control — Ensamble Curupayty')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <style>
        :root {
            --bg-dark: #07090c;
            --bg-sidebar: #0b0e14;
            --bg-surface: #10151f;
            --bg-surface-elevated: #18202e;
            --accent-gold: #e5a93c;
            --accent-gold-hover: #f39c12;
            --accent-gold-glow: rgba(229, 169, 60, 0.2);
            --accent-red: #e74c3c;
            --accent-green: #2ecc71;
            --border-color: #1f283a;
            --text-primary: #ffffff;
            --text-secondary: #94a3b8;
            --text-muted: #64748b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-primary);
            font-family: 'Outfit', sans-serif;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: var(--bg-sidebar);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 40;
        }

        .sidebar-brand {
            padding: 1.5rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-bottom: 1px solid var(--border-color);
            text-decoration: none;
            color: #fff;
        }

        .sidebar-nav {
            padding: 1.25rem 0.75rem;
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
            flex: 1;
            overflow-y: auto;
        }

        .nav-category {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: var(--text-muted);
            padding: 0.75rem 0.75rem 0.35rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.65rem 0.85rem;
            border-radius: 6px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .sidebar-link:hover, .sidebar-link.active {
            background: rgba(229, 169, 60, 0.1);
            color: var(--accent-gold);
        }

        .badge-count {
            background: var(--accent-gold);
            color: #000;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 0.15rem 0.5rem;
            border-radius: 12px;
        }

        /* Main Content */
        .main-wrapper {
            margin-left: 260px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .topbar {
            background: var(--bg-surface);
            border-bottom: 1px solid var(--border-color);
            padding: 0.85rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 30;
        }

        .content-area {
            padding: 2rem;
            flex: 1;
            max-width: 1300px;
            width: 100%;
            margin: 0 auto;
        }

        /* Componentes Comunes */
        .card {
            background: var(--bg-surface);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .btn-gold {
            background: linear-gradient(135deg, #e5a93c, #cf9128);
            color: #07090c !important;
            font-weight: 700;
            font-size: 0.88rem;
            padding: 0.55rem 1.15rem;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(229, 169, 60, 0.25);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-gold:hover {
            background: linear-gradient(135deg, #f59e0b, #e5a93c);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(229, 169, 60, 0.4);
        }

        .btn-sm {
            padding: 0.4rem 0.85rem;
            font-size: 0.82rem;
            border-radius: 6px;
        }

        .btn-outline {
            background: rgba(255, 255, 255, 0.03);
            color: var(--text-secondary);
            border: 1px solid var(--border-color);
            padding: 0.55rem 1.15rem;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.88rem;
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-outline:hover {
            background: rgba(229, 169, 60, 0.08);
            border-color: var(--accent-gold);
            color: var(--accent-gold);
            transform: translateY(-1px);
        }

        /* Tablas */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }
        th {
            text-align: left;
            padding: 0.85rem 1rem;
            background: var(--bg-surface-elevated);
            color: var(--text-secondary);
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid var(--border-color);
        }
        td {
            padding: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
            color: var(--text-primary);
        }
        tr:hover td {
            background: rgba(229, 169, 60, 0.02);
        }

        /* Inputs */
        .form-group {
            margin-bottom: 1.25rem;
        }
        .form-label {
            display: block;
            margin-bottom: 0.4rem;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-secondary);
        }
        .form-control {
            width: 100%;
            background: #090c12;
            border: 1px solid var(--border-color);
            color: #fff;
            padding: 0.65rem 0.9rem;
            border-radius: 6px;
            font-size: 0.92rem;
            outline: none;
            transition: border 0.2s;
        }
        .form-control:focus {
            border-color: var(--accent-gold);
            box-shadow: 0 0 0 2px var(--accent-gold-glow);
        }

        /* Alertas */
        .alert-box {
            padding: 0.85rem 1.25rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }
        .alert-success {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #4ade80;
        }
        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #f87171;
        }

        @media (max-width: 900px) {
            .sidebar {
                width: 70px;
            }
            .sidebar-link span, .sidebar-brand div, .nav-category {
                display: none;
            }
            .main-wrapper {
                margin-left: 70px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Sidebar de Navegación -->
    <aside class="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
            <img src="{{ asset('images/logo_curupayty.png') }}" alt="Curupayty" style="width: 48px; height: 48px; object-fit: contain; filter: drop-shadow(0 0 10px rgba(229, 169, 60, 0.45));">
            <div>
                <div style="font-weight: 800; font-size: 0.98rem;">CURUPAYTY</div>
                <div style="font-size: 0.65rem; color: var(--accent-gold); letter-spacing: 0.1em; text-transform: uppercase;">Administración</div>
            </div>
        </a>

        <ul class="sidebar-nav">
            <li class="nav-category">Panel Principal</li>
            <li>
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span>📊 Dashboard / Métricas</span>
                </a>
            </li>

            <li class="nav-category">Acta Fundacional</li>
            <li>
                <a href="{{ route('admin.firmantes.index') }}" class="sidebar-link {{ request()->routeIs('admin.firmantes.*') && !request()->routeIs('admin.firmantes.acta_oficial') ? 'active' : '' }}">
                    <span>✍️ Padrón de Firmantes</span>
                    <span class="badge-count">{{ \App\Models\FirmanteActa::count() }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.firmantes.acta_oficial') }}" target="_blank" class="sidebar-link {{ request()->routeIs('admin.firmantes.acta_oficial') ? 'active' : '' }}">
                    <span>📜 Acta Oficial Imprimible</span>
                </a>
            </li>

            <li class="nav-category">Control de Acceso</li>
            <li>
                <a href="{{ route('admin.roles.index') }}" class="sidebar-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                    <span>🛡️ Roles y Permisos</span>
                </a>
            </li>

            <li class="nav-category">Producción Musical</li>
            <li>
                <a href="{{ route('admin.obras.index') }}" class="sidebar-link {{ request()->routeIs('admin.obras.*') ? 'active' : '' }}">
                    <span>🎼 Catálogo de Obras</span>
                    <span class="badge-count" style="background: #2563eb; color: #fff;">{{ \App\Models\Obra::count() }}</span>
                </a>
            </li>

            <li class="nav-category">Portal Web (CMS)</li>
            <li>
                <a href="{{ route('admin.paginas.index') }}" class="sidebar-link {{ request()->routeIs('admin.paginas.*') ? 'active' : '' }}">
                    <span>📄 Páginas Institucionales</span>
                </a>
            </li>

            <li class="nav-category">Enlaces</li>
            <li>
                <a href="{{ route('landing') }}" target="_blank" class="sidebar-link">
                    <span>🌐 Ver Portal Público</span>
                </a>
            </li>
            <li>
                <a href="{{ route('catalogo.index') }}" target="_blank" class="sidebar-link">
                    <span>🎻 Ver Tienda Catálogo</span>
                </a>
            </li>
        </ul>

        <div style="padding: 1rem; border-top: 1px solid var(--border-color);">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-outline" style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 0.5rem; font-size: 0.85rem;">
                    <span>Cerrar Sesión</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <!-- Topbar -->
        <header class="topbar">
            <div>
                <h3 style="font-size: 1.1rem; font-weight: 700; color: #fff;">@yield('header_title', View::yieldContent('header-title', 'Administración'))</h3>
            </div>
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="text-align: right;">
                    <div style="font-size: 0.88rem; font-weight: 600;">{{ Auth::user()->name }}</div>
                    <div style="font-size: 0.72rem; color: var(--accent-gold);">
                        {{ Auth::user()->roles->pluck('display_name')->implode(', ') ?: 'Usuario' }}
                    </div>
                </div>
            </div>
        </header>

        <!-- Flash alerts -->
        <div class="content-area">
            @if(session('success'))
                <div class="alert-box alert-success">
                    ✓ {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert-box alert-danger">
                    ⚠ {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- SweetAlert2 Modern Dark/Gold Alert Suite -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .curupayty-swal-popup {
            background: #0d1118 !important;
            border: 1px solid rgba(229, 169, 60, 0.45) !important;
            border-radius: 14px !important;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.95), 0 0 30px rgba(229, 169, 60, 0.15) !important;
            font-family: 'Outfit', -apple-system, BlinkMacSystemFont, sans-serif !important;
            padding: 1.8rem 1.5rem !important;
        }
        .curupayty-swal-popup .swal2-title {
            color: #f6d28b !important;
            font-size: 1.3rem !important;
            font-weight: 700 !important;
            letter-spacing: 0.02em !important;
            margin-bottom: 0.8rem !important;
        }
        .curupayty-swal-popup .swal2-html-container {
            color: #cbd5e1 !important;
            font-size: 0.95rem !important;
            line-height: 1.6 !important;
            margin: 0.5rem 0 1.5rem !important;
        }
        .curupayty-swal-popup .swal2-html-container strong {
            color: #ffffff !important;
        }
        .curupayty-swal-actions {
            gap: 0.75rem !important;
            margin-top: 0.5rem !important;
        }
        .curupayty-swal-gold-btn {
            background: linear-gradient(135deg, #e5a93c, #cf9128) !important;
            color: #07090c !important;
            font-weight: 800 !important;
            font-size: 0.88rem !important;
            letter-spacing: 0.04em !important;
            padding: 0.65rem 1.4rem !important;
            border-radius: 7px !important;
            border: none !important;
            cursor: pointer !important;
            transition: all 0.2s !important;
            box-shadow: 0 4px 14px rgba(229, 169, 60, 0.35) !important;
        }
        .curupayty-swal-gold-btn:hover {
            box-shadow: 0 0 20px rgba(229, 169, 60, 0.6) !important;
            transform: translateY(-1px) !important;
        }
        .curupayty-swal-danger-btn {
            background: #dc2626 !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            font-size: 0.88rem !important;
            padding: 0.65rem 1.4rem !important;
            border-radius: 7px !important;
            border: none !important;
            cursor: pointer !important;
            transition: all 0.2s !important;
            box-shadow: 0 4px 14px rgba(220, 38, 38, 0.4) !important;
        }
        .curupayty-swal-danger-btn:hover {
            background: #b91c1c !important;
            box-shadow: 0 0 20px rgba(220, 38, 38, 0.6) !important;
            transform: translateY(-1px) !important;
        }
        .curupayty-swal-cancel-btn {
            background: #18202e !important;
            color: #94a3b8 !important;
            font-weight: 600 !important;
            font-size: 0.88rem !important;
            padding: 0.65rem 1.2rem !important;
            border-radius: 7px !important;
            border: 1px solid #334155 !important;
            cursor: pointer !important;
            transition: all 0.2s !important;
        }
        .curupayty-swal-cancel-btn:hover {
            background: #253349 !important;
            color: #ffffff !important;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // 1. Auto-convert any legacy form with onsubmit="return confirm(...)"
            document.querySelectorAll('form[onsubmit*="confirm"]').forEach(form => {
                const rawOnsubmit = form.getAttribute('onsubmit') || '';
                const match = rawOnsubmit.match(/confirm\(['"](.*?)['"]\)/);
                const msg = match ? match[1] : '¿Estás seguro de continuar con esta acción?';
                form.removeAttribute('onsubmit');
                form.setAttribute('data-confirm', msg);
                
                if (/eliminar|delete|borrar/i.test(msg)) {
                    form.setAttribute('data-danger', 'true');
                    form.setAttribute('data-title', '¿Eliminar Registro?');
                    form.setAttribute('data-btn', 'Sí, eliminar definitivamente');
                    form.setAttribute('data-icon', 'warning');
                } else if (/reenviar|correo|email/i.test(msg)) {
                    form.setAttribute('data-title', 'Reenviar Acta Fundacional');
                    form.setAttribute('data-btn', 'Sí, enviar ahora');
                    form.setAttribute('data-icon', 'info');
                }
            });

            // 2. Intercept forms with data-confirm
            document.addEventListener('submit', function(e) {
                const form = e.target;
                if (form && form.hasAttribute('data-confirm')) {
                    e.preventDefault();
                    const text = form.getAttribute('data-confirm');
                    const title = form.getAttribute('data-title') || 'Confirmación Oficial';
                    const isDanger = form.getAttribute('data-danger') === 'true';
                    const icon = form.getAttribute('data-icon') || (isDanger ? 'warning' : 'question');
                    const confirmBtn = form.getAttribute('data-btn') || (isDanger ? 'Sí, eliminar' : 'Sí, confirmar');

                    Swal.fire({
                        title: title,
                        html: text,
                        icon: icon,
                        showCancelButton: true,
                        confirmButtonText: confirmBtn,
                        cancelButtonText: 'Cancelar',
                        buttonsStyling: false,
                        backdrop: 'rgba(5, 7, 10, 0.85)',
                        customClass: {
                            popup: 'curupayty-swal-popup',
                            confirmButton: isDanger ? 'curupayty-swal-danger-btn' : 'curupayty-swal-gold-btn',
                            cancelButton: 'curupayty-swal-cancel-btn',
                            actions: 'curupayty-swal-actions'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.removeAttribute('data-confirm');
                            form.submit();
                        }
                    });
                }
            });

            // 3. Modern Toasts for Laravel Flash Sessions
            @if(session('success'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                    background: '#0d1118',
                    color: '#f8fafc',
                    iconColor: '#34d399',
                    customClass: {
                        popup: 'curupayty-swal-popup'
                    }
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: "{{ session('error') }}",
                    showConfirmButton: false,
                    timer: 6000,
                    timerProgressBar: true,
                    background: '#0d1118',
                    color: '#f8fafc',
                    iconColor: '#f87171',
                    customClass: {
                        popup: 'curupayty-swal-popup'
                    }
                });
            @endif
        });
    </script>

    @stack('scripts')
</body>
</html>
