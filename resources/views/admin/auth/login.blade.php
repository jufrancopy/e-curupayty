<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Administrativo — Ensamble Curupayty</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800;900&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --bg-dark: #07090c;
            --bg-card: #0d1117;
            --gold-primary: #e5a93c;
            --gold-light: #f3c267;
            --border-gold: rgba(229, 169, 60, 0.3);
            --border-subtle: rgba(255, 255, 255, 0.08);
            --text-light: #f8fafc;
            --text-muted: #94a3b8;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            background-color: var(--bg-dark);
            color: var(--text-light);
            font-family: 'Outfit', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            background-image: radial-gradient(circle at 50% 20%, rgba(229, 169, 60, 0.12), transparent 60%);
        }
        .login-card {
            background: var(--bg-card);
            border: 1px solid var(--border-gold);
            border-radius: 14px;
            width: 100%;
            max-width: 440px;
            padding: 2.75rem 2.25rem;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.8);
            position: relative;
        }
        .login-brand {
            text-align: center;
            margin-bottom: 2rem;
        }
        .brand-icon {
            font-size: 2.5rem;
            color: var(--gold-primary);
            margin-bottom: 0.75rem;
            display: inline-block;
        }
        .login-brand h1 {
            font-family: 'Cinzel', serif;
            font-size: 1.4rem;
            letter-spacing: 0.08em;
            color: var(--gold-light);
            margin-bottom: 0.25rem;
        }
        .login-brand p {
            font-size: 0.82rem;
            color: var(--text-muted);
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }
        .alert {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.88rem;
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }
        .form-group {
            margin-bottom: 1.25rem;
        }
        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 0.4rem;
            color: #cbd5e1;
        }
        .input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }
        .input-wrap i {
            position: absolute;
            left: 1rem;
            color: var(--text-muted);
            font-size: 0.95rem;
        }
        .form-control {
            width: 100%;
            background: #080b10;
            border: 1px solid var(--border-subtle);
            border-radius: 8px;
            padding: 0.75rem 1rem 0.75rem 2.6rem;
            color: var(--text-light);
            font-size: 0.95rem;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-control:focus {
            border-color: var(--gold-primary);
            box-shadow: 0 0 0 3px rgba(229, 169, 60, 0.15);
        }
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            font-size: 0.85rem;
        }
        .remember-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            color: var(--text-muted);
        }
        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, var(--gold-primary) 0%, #c48b26 100%);
            color: #07090c;
            border: none;
            border-radius: 8px;
            padding: 0.85rem;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(229, 169, 60, 0.35);
        }
        .login-footer {
            margin-top: 1.75rem;
            text-align: center;
            font-size: 0.82rem;
            color: var(--text-muted);
        }
        .login-footer a {
            color: var(--gold-primary);
            text-decoration: none;
        }
        .login-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-brand">
            <img src="{{ asset('images/logo_curupayty.png') }}" alt="Ensamble Curupayty" style="height: 110px; width: 110px; object-fit: contain; margin-bottom: 1rem; filter: drop-shadow(0 0 20px rgba(229, 169, 60, 0.5));">
            <h1>Ensamble Curupayty</h1>
            <p>Portal de Gestión & Auditoría Institucional</p>
        </div>

        @if(session('error'))
            <div class="alert">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <form action="{{ route('admin.login.post') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="email">Correo Electrónico Oficial</label>
                <div class="input-wrap">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', 'admin@curupayty.com') }}" required autofocus>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Contraseña de Seguridad</label>
                <div class="input-wrap">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" id="password" class="form-control" required placeholder="••••••••">
                </div>
            </div>

            <div class="form-options">
                <label class="remember-label">
                    <input type="checkbox" name="remember" value="1" checked>
                    <span>Recordar sesión</span>
                </label>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-sign-in-alt"></i> Ingresar al Sistema
            </button>
        </form>

        <div class="login-footer">
            <a href="{{ route('landing') }}"><i class="fas fa-arrow-left"></i> Regresar al Sitio Principal</a>
        </div>
    </div>
</body>
</html>
