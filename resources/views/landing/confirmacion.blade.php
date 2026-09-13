@extends('layouts.app')

@section('title', 'Constancia Oficial de Firma — Ensamble Curupayty')

@section('content')
<div class="confirmacion-page">
    <div class="container-medium">

        <div class="constancia-card">
            
            <div class="constancia-badge">
                DOCUMENTO OFICIAL · ASAMBLEA FUNDACIONAL
            </div>

            <div style="text-align: center; margin: 1.5rem 0;">
                <img src="{{ asset('images/logo_curupayty.png') }}" alt="Curupayty" style="height: 115px; width: 115px; object-fit: contain; filter: drop-shadow(0 0 20px rgba(229, 169, 60, 0.5));">
            </div>

            <h1 class="constancia-title">CONSTANCIA DE ADHESIÓN AL ACTA FUNDACIONAL</h1>
            <p class="constancia-subtitle">Ensamble Curupayty — Atypu</p>

            <div class="constancia-alert">
                ✓ <strong>¡Registro completado con éxito!</strong> Tu firma manuscrita digital y datos han sido incorporados al padrón oficial de socios creadores. 
                <span style="display: block; margin-top: 6px; color: #cbd5e1; font-size: 0.88rem;">
                    ✉️ Te hemos enviado una copia oficial del Acta con tu <strong>certificado en imagen de alta resolución</strong> a tu correo: <strong>{{ $firmante->user->email ?? 'tu correo' }}</strong>.
                </span>
            </div>

            <!-- Ficha de Datos del Firmante -->
            <div class="constancia-details-grid">
                <div class="detail-item">
                    <span class="detail-label">Socio Firmante</span>
                    <span class="detail-val">{{ $firmante->nombre_completo }}</span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Cédula de Identidad</span>
                    <span class="detail-val">{{ $firmante->cedula }}</span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Instrumento / Rol</span>
                    <span class="detail-val" style="color: var(--accent-gold); font-weight: 700;">{{ $firmante->instrumento }}</span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Ciudad de Residencia</span>
                    <span class="detail-val">{{ $firmante->ciudad }}</span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Fecha y Hora de Firma</span>
                    <span class="detail-val">{{ $firmante->created_at->format('d/m/Y H:i:s') }}</span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Código de Verificación Único</span>
                    <span class="detail-val code-val">{{ $firmante->codigo_verificacion }}</span>
                </div>
            </div>

            <!-- Sueño Musical -->
            <div class="sueno-box">
                <div class="sueno-title">Declaración de Sueño Musical:</div>
                <div class="sueno-text">"{{ $firmante->sueno_musical }}"</div>
            </div>

            <!-- Firma Manuscrita Renderizada -->
            <div class="signature-stamp-box">
                <div class="stamp-label">Rúbrica Manuscrita Digital Asentada:</div>
                <div class="signature-img-wrapper">
                    <img src="{{ $firmante->firma_digital }}" alt="Firma digital de {{ $firmante->nombre_completo }}" class="signature-display-img">
                </div>
                <div class="stamp-footer">
                    Estado: <span class="badge-status">Aguardando Calendario de la Asamblea Fundacional</span>
                </div>
            </div>

            <!-- Acciones -->
            <div class="constancia-actions" style="display: flex; gap: 0.85rem; justify-content: center; flex-wrap: wrap; margin-top: 1rem;">
                <a href="{{ route('acta.descargar_imagen', $firmante->codigo_verificacion) }}" class="btn-gold" style="display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none;">
                    <i class="fas fa-file-image"></i> Descargar Imagen Certificada (PNG)
                </a>
                <button onclick="window.print()" class="btn-outline" style="display: inline-flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-print"></i> Imprimir / Guardar en PDF
                </button>
                <form action="{{ route('acta.reenviar_correo', $firmante->codigo_verificacion) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn-outline" style="display: inline-flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                        <i class="fas fa-envelope"></i> Reenviar a mi Correo
                    </button>
                </form>
                <a href="{{ route('catalogo.index') }}" class="btn-outline" style="display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none;">
                    <i class="fas fa-music"></i> Catálogo de Obras
                </a>
            </div>

        </div>

    </div>
</div>
@endsection

@push('styles')
<style>
    .confirmacion-page {
        padding: 4rem 1rem;
    }
    .constancia-card {
        background: linear-gradient(180deg, #131825 0%, #0d111a 100%);
        border: 2px solid var(--accent-gold);
        border-radius: 16px;
        padding: 3rem 2.5rem;
        text-align: center;
        box-shadow: 0 20px 60px rgba(0,0,0,0.8), 0 0 35px var(--accent-gold-glow);
    }
    .constancia-badge {
        display: inline-block;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.15em;
        color: var(--accent-gold);
        background: rgba(229, 169, 60, 0.1);
        padding: 0.3rem 0.85rem;
        border-radius: 20px;
        border: 1px solid var(--accent-gold);
        margin-bottom: 1.25rem;
    }
    .constancia-crest {
        width: 48px;
        height: 48px;
        margin: 0 auto 1rem;
        background: linear-gradient(135deg, #e5a93c, #cf9128);
        color: #000;
        font-family: 'Cinzel', serif;
        font-weight: 900;
        font-size: 1.4rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }
    .constancia-title {
        font-size: 1.6rem;
        color: #fff;
        letter-spacing: 0.05em;
        margin-bottom: 0.35rem;
    }
    .constancia-subtitle {
        color: var(--accent-gold);
        font-size: 0.9rem;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        margin-bottom: 2rem;
    }
    .constancia-alert {
        background: rgba(46, 204, 113, 0.12);
        border: 1px solid rgba(46, 204, 113, 0.4);
        color: #2ecc71;
        padding: 0.85rem 1.25rem;
        border-radius: 8px;
        font-size: 0.95rem;
        margin-bottom: 2rem;
        text-align: left;
    }
    .constancia-details-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
        text-align: left;
        background: #090c12;
        border: 1px solid var(--border-color);
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    @media(max-width: 640px) {
        .constancia-details-grid {
            grid-template-columns: 1fr;
        }
    }
    .detail-item {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    .detail-label {
        font-size: 0.78rem;
        color: var(--text-muted);
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.05em;
    }
    .detail-val {
        font-size: 1rem;
        color: #ffffff;
        font-weight: 600;
    }
    .code-val {
        font-family: monospace;
        color: var(--accent-gold);
        font-size: 1.1rem;
        letter-spacing: 0.1em;
    }
    .sueno-box {
        background: #090c12;
        border: 1px solid var(--border-color);
        border-radius: 10px;
        padding: 1.25rem;
        text-align: left;
        margin-bottom: 1.75rem;
    }
    .sueno-title {
        font-size: 0.8rem;
        color: var(--accent-gold);
        font-weight: 700;
        margin-bottom: 0.35rem;
    }
    .sueno-text {
        font-style: italic;
        color: var(--text-secondary);
        font-size: 0.95rem;
        line-height: 1.5;
    }
    .signature-stamp-box {
        background: #06080c;
        border: 2px dashed rgba(229, 169, 60, 0.4);
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 2.25rem;
    }
    .stamp-label {
        font-size: 0.82rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 0.75rem;
    }
    .signature-img-wrapper {
        max-width: 320px;
        height: 110px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(229, 169, 60, 0.03);
        border-radius: 8px;
        padding: 0.5rem;
    }
    .signature-display-img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        filter: drop-shadow(0 0 8px rgba(229, 169, 60, 0.6));
    }
    .stamp-footer {
        margin-top: 0.75rem;
        font-size: 0.82rem;
        color: var(--text-secondary);
    }
    .badge-status {
        color: var(--accent-gold);
        font-weight: 700;
    }
    .constancia-actions {
        display: flex;
        justify-content: center;
        gap: 1.25rem;
        flex-wrap: wrap;
    }

    @media print {
        .navbar, .footer, .constancia-actions {
            display: none !important;
        }
        body {
            background: #fff !important;
            color: #000 !important;
        }
        .constancia-card {
            border: 2px solid #000 !important;
            background: #fff !important;
            color: #000 !important;
            box-shadow: none !important;
        }
        .detail-val, .constancia-title, .sueno-text {
            color: #000 !important;
        }
        .code-val, .detail-label, .constancia-subtitle {
            color: #333 !important;
        }
        .signature-display-img {
            filter: invert(1) !important;
        }
    }
</style>
@endpush
