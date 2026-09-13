<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Ensamble Curupayty — Atypu')</title>
    <meta name="description" content="@yield('meta_description', 'Colectivo orquestal autogestionado de creadores-intérpretes. No venimos a interpretar el pasado. Venimos a firmar el futuro.')">
    
    <link rel="icon" type="image/png" href="{{ asset('images/logo_curupayty.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo_curupayty.png') }}">
    
    <!-- Fuentes Cinemáticas de Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800;900&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-dark: #07090c;
            --bg-surface: #10141d;
            --bg-surface-elevated: #181f2c;
            --accent-gold: #e5a93c;
            --accent-gold-hover: #f39c12;
            --accent-gold-glow: rgba(229, 169, 60, 0.25);
            --accent-red: #e74c3c;
            --border-color: #222b3d;
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
            font-family: 'Outfit', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            background-image: 
                radial-gradient(ellipse 80% 50% at 50% -20%, rgba(229, 169, 60, 0.08), transparent 70%),
                radial-gradient(ellipse 60% 40% at 50% 100%, rgba(231, 76, 60, 0.05), transparent 70%);
        }

        h1, h2, h3, .font-cinzel {
            font-family: 'Cinzel', serif;
            letter-spacing: 0.04em;
        }

        /* Contenedor Universal Centrado con Márgenes */
        .container {
            width: 100%;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
            padding-left: 2.5rem;
            padding-right: 2.5rem;
        }

        @media (min-width: 1440px) {
            .container {
                max-width: 1240px;
                padding-left: 3rem;
                padding-right: 3rem;
            }
        }

        @media (max-width: 992px) {
            .container {
                padding-left: 2rem;
                padding-right: 2rem;
            }
        }

        @media (max-width: 768px) {
            .container {
                padding-left: 1.25rem;
                padding-right: 1.25rem;
            }
        }

        /* Barra de Navegación */
        .navbar {
            background: rgba(16, 20, 29, 0.85);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 50;
            transition: all 0.3s ease;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0.85rem 2.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        @media (min-width: 1440px) {
            .nav-container {
                max-width: 1240px;
                padding-left: 3rem;
                padding-right: 3rem;
            }
        }

        @media (max-width: 992px) {
            .nav-container {
                padding-left: 2rem;
                padding-right: 2rem;
            }
        }

        @media (max-width: 768px) {
            .nav-container {
                padding: 0.85rem 1.25rem;
            }
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: var(--text-primary);
        }

        .nav-logo-img {
            height: 56px;
            width: 56px;
            object-fit: contain;
            filter: drop-shadow(0 0 12px rgba(229, 169, 60, 0.45));
            transition: transform 0.25s ease;
        }
        .nav-logo-img:hover {
            transform: scale(1.08);
        }

        .brand-title {
            font-size: 1.18rem;
            font-weight: 800;
            letter-spacing: 0.06em;
            color: #ffffff;
        }

        .brand-subtitle {
            font-size: 0.72rem;
            color: var(--accent-gold);
            letter-spacing: 0.16em;
            text-transform: uppercase;
            font-weight: 700;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 1.75rem;
            list-style: none;
        }

        .nav-link {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.92rem;
            font-weight: 500;
            transition: all 0.2s ease;
            position: relative;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--accent-gold);
        }

        .btn-gold {
            background: linear-gradient(135deg, #e5a93c, #cf9128);
            color: #07090c;
            font-weight: 700;
            font-size: 0.88rem;
            letter-spacing: 0.03em;
            padding: 0.6rem 1.25rem;
            border-radius: 6px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.25s ease;
            box-shadow: 0 4px 15px rgba(229, 169, 60, 0.25);
            border: none;
            cursor: pointer;
        }

        .btn-gold:hover {
            background: linear-gradient(135deg, #f39c12, #e5a93c);
            box-shadow: 0 6px 22px rgba(229, 169, 60, 0.4);
            transform: translateY(-1px);
            color: #000;
        }

        .btn-outline {
            background: transparent;
            color: var(--text-primary);
            border: 1px solid var(--border-color);
            padding: 0.6rem 1.25rem;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 600;
            transition: all 0.2s;
            cursor: pointer;
        }

        .btn-outline:hover {
            border-color: var(--accent-gold);
            color: var(--accent-gold);
            background: rgba(229, 169, 60, 0.05);
        }

        /* Alertas */
        .alert-box {
            max-width: 1200px;
            margin: 1rem auto 0;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.95rem;
        }
        .alert-success {
            background: rgba(34, 197, 94, 0.12);
            border: 1px solid rgba(34, 197, 94, 0.4);
            color: #4ade80;
        }
        .alert-danger {
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.4);
            color: #f87171;
        }

        /* Mobile Menu */
        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--text-primary);
            font-size: 1.5rem;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .mobile-toggle {
                display: block;
            }
            .nav-links {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                background: var(--bg-surface);
                border-bottom: 1px solid var(--border-color);
                flex-direction: column;
                padding: 1.5rem;
                gap: 1.25rem;
            }
            .nav-links.active {
                display: flex;
            }
        }

        /* Footer */
        .footer {
            margin-top: auto;
            background: #05070a;
            border-top: 1px solid var(--border-color);
            padding: 3.5rem 2.5rem 2rem;
            font-size: 0.88rem;
            color: var(--text-muted);
        }
        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 2.5rem;
        }
        .footer-bottom {
            max-width: 1200px;
            margin: 2.5rem auto 0;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.8rem;
        }
        @media (min-width: 1440px) {
            .footer-container, .footer-bottom {
                max-width: 1240px;
            }
            .footer {
                padding-left: 3rem;
                padding-right: 3rem;
            }
        }
        @media (max-width: 992px) {
            .footer {
                padding-left: 2rem;
                padding-right: 2rem;
            }
        }
        @media (max-width: 768px) {
            .footer {
                padding: 2.5rem 1.25rem 2rem;
            }
            .footer-container {
                grid-template-columns: 1fr;
                gap: 1.75rem;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Navegación -->
    <nav class="navbar" id="mainSiteNavbar" @if(View::hasSection('hide_navbar')) style="display: none;" @endif>
        <div class="nav-container">
            <a href="{{ route('landing') }}" class="brand-logo">
                <img src="{{ asset('images/logo_curupayty.png') }}" alt="Curupayty" class="nav-logo-img">
                <div>
                    <div class="brand-title">CURUPAYTY</div>
                    <div class="brand-subtitle">Atypu</div>
                </div>
            </a>

            <button class="mobile-toggle" onclick="document.querySelector('.nav-links').classList.toggle('active')">☰</button>

            <ul class="nav-links">
                <li><a href="{{ route('landing') }}#video" class="nav-link">Video</a></li>
                <li><a href="{{ route('landing') }}#acta" class="nav-link" style="color: var(--accent-gold); font-weight: 600;">Firmar Acta</a></li>
                <li><a href="{{ route('catalogo.index') }}" class="nav-link">Catálogo de Obras</a></li>
                <li><a href="{{ route('pagina.show', 'manifiesto') }}" class="nav-link">Manifiesto</a></li>
                <li><a href="{{ route('pagina.show', 'estatuto') }}" class="nav-link">Estatuto</a></li>
                <li><a href="{{ route('pagina.show', 'asamblea') }}" class="nav-link">Asamblea</a></li>
                
                @auth
                    <li><a href="{{ route('admin.dashboard') }}" class="btn-outline">Panel Admin</a></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="nav-link" style="background:none; border:none; cursor:pointer;">Salir</button>
                        </form>
                    </li>
                @else
                    <li><a href="{{ route('login') }}" class="btn-outline">Acceso</a></li>
                @endauth
            </ul>
        </div>
    </nav>

    <!-- Notificaciones Flash -->
    @if(session('success'))
        <div class="alert-box alert-success">
            <span>✓</span> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert-box alert-danger">
            <span>⚠</span> {{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div class="alert-box alert-danger" style="flex-direction: column; align-items: flex-start;">
            <div style="font-weight: 600;">Por favor revise los siguientes errores:</div>
            <ul style="margin-left: 1.25rem; margin-top: 0.25rem;">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Contenido Principal -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer" id="mainSiteFooter" @if(View::hasSection('hide_navbar')) style="display: none;" @endif>
        <div class="footer-container">
            <div>
                <h4 style="color: #fff; margin-bottom: 0.75rem; font-family: 'Cinzel', serif;">ENSAMBLE CURUPAYTY · ATYPU</h4>
                <p style="line-height: 1.6; max-width: 480px;">
                    Colectivo orquestal autogestionado de creadores-intérpretes. 
                    Música inédita para cine, series, catalogación comercial y streaming.
                    Curupayty es la actitud. Atypu es la forma en que se organiza esa actitud puertas adentro.
                </p>
            </div>
            <div>
                <h5 style="color: var(--accent-gold); margin-bottom: 0.75rem;">Marco Institucional</h5>
                <ul style="list-style: none; display: flex; flex-direction: column; gap: 0.5rem;">
                    <li><a href="{{ route('pagina.show', 'manifiesto') }}" style="color: var(--text-secondary); text-decoration: none;">Manifiesto de Ruptura</a></li>
                    <li><a href="{{ route('pagina.show', 'estatuto') }}" style="color: var(--text-secondary); text-decoration: none;">Estatuto y Reparto 40/25/10/25</a></li>
                    <li><a href="{{ route('pagina.show', 'asamblea') }}" style="color: var(--text-secondary); text-decoration: none;">Calendario de Asamblea</a></li>
                    <li><a href="{{ route('catalogo.index') }}" style="color: var(--text-secondary); text-decoration: none;">Tienda de Partituras</a></li>
                </ul>
            </div>
            <div>
                <h5 style="color: var(--accent-gold); margin-bottom: 0.75rem;">Protección Intelectual</h5>
                <p style="line-height: 1.6;">
                    Registro obligatorio DINAPI · APA · AIE.<br>
                    Asunción, Paraguay.<br>
                    <span style="color: #fff; font-weight: 600;">Contacto:</span> contacto@curupayty.com
                </p>
            </div>
        </div>
        <div class="footer-bottom">
            <div>&copy; {{ date('Y') }} Ensamble Curupayty — Todos los derechos reservados.</div>
            <div>No venimos a interpretar el pasado. Venimos a firmar el futuro.</div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
