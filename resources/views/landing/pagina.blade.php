@extends('layouts.app')

@section('title', $pagina->titulo . ' — Ensamble Curupayty · Atypu')
@section('meta_description', $pagina->meta_descripcion ?? 'Manifiesto y Estatuto Fundacional del Ensamble Curupayty · Atypu')

@section('content')
<div class="institutional-page">
    <div class="institutional-hero">
        <div class="container">
            <a href="{{ route('landing') }}" class="back-link">
                <i class="fas fa-arrow-left"></i> Volver al Portal Principal
            </a>
            <div class="badge-tag">DOCUMENTO INSTITUCIONAL OFICIAL</div>
            <h1 class="page-title">{{ $pagina->titulo }}</h1>
            <p class="page-subtitle">{{ $pagina->subtitulo }}</p>
            
            <div class="page-meta">
                <span><i class="far fa-calendar-alt"></i> Publicado: {{ $pagina->created_at ? $pagina->created_at->format('d/m/Y') : date('d/m/Y') }}</span>
                <span><i class="fas fa-shield-alt"></i> Versión Aprobada por la Comisión Fundacional</span>
                <span><i class="fas fa-landmark"></i> Ensamble Curupayty · Atypu</span>
            </div>

            <!-- Navegación entre Documentos Rectores -->
            <div class="document-tabs">
                <a href="{{ route('pagina.show', 'manifiesto') }}" class="doc-tab {{ $pagina->slug === 'manifiesto' ? 'active' : '' }}">
                    <i class="fas fa-bullhorn"></i> Manifiesto de Ruptura
                </a>
                <a href="{{ route('pagina.show', 'estatuto') }}" class="doc-tab {{ $pagina->slug === 'estatuto' ? 'active' : '' }}">
                    <i class="fas fa-balance-scale"></i> Estatuto Fundacional
                </a>
                <a href="{{ route('pagina.show', 'asamblea') }}" class="doc-tab {{ $pagina->slug === 'asamblea' ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Asamblea Fundacional
                </a>
            </div>
        </div>
    </div>

    <div class="container page-content-wrap">
        <div class="document-paper">
            <div class="document-watermark">CURUPAYTY</div>
            <div class="document-body">
                {!! Str::markdown($pagina->contenido) !!}
            </div>
            
            <div class="document-footer">
                <div class="footer-seal">
                    <i class="fas fa-certificate seal-icon"></i>
                    <div>
                        <strong>Ensamble Curupayty · Atypu</strong>
                        <p>Documento oficial incorporado al repositorio del Acta Fundacional</p>
                    </div>
                </div>
                <div class="action-cta">
                    <a href="{{ route('landing') }}#acta" class="btn btn-gold">
                        <i class="fas fa-pen-nib"></i> Firmar Acta Fundacional
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.institutional-hero {
    background: radial-gradient(circle at 50% 0%, rgba(229,169,60,0.18), transparent 70%), var(--bg-card);
    border-bottom: 1px solid var(--border-gold);
    padding: 3.5rem 0 2.5rem;
    text-align: center;
}
.back-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-muted);
    font-size: 0.88rem;
    margin-bottom: 1.25rem;
    transition: color 0.2s;
    text-decoration: none;
}
.back-link:hover {
    color: var(--gold-primary);
}
.badge-tag {
    display: inline-block;
    background: rgba(229, 169, 60, 0.12);
    color: var(--gold-primary);
    border: 1px solid var(--border-gold);
    padding: 0.35rem 0.9rem;
    border-radius: 20px;
    font-size: 0.75rem;
    letter-spacing: 0.12em;
    font-weight: 700;
    margin-bottom: 0.85rem;
}
.page-title {
    font-size: 2.4rem;
    margin: 0.5rem 0;
    color: var(--text-light);
    font-family: 'Cinzel', serif;
}
.page-subtitle {
    color: var(--gold-light);
    font-size: 1.15rem;
    max-width: 780px;
    margin: 0 auto 1.5rem;
    line-height: 1.6;
}
.page-meta {
    display: flex;
    justify-content: center;
    gap: 1.75rem;
    font-size: 0.85rem;
    color: var(--text-muted);
    flex-wrap: wrap;
    margin-bottom: 2rem;
}
.page-meta span {
    display: flex;
    align-items: center;
    gap: 0.4rem;
}

/* Document Tabs */
.document-tabs {
    display: flex;
    justify-content: center;
    gap: 0.75rem;
    flex-wrap: wrap;
    margin-top: 1rem;
}
.doc-tab {
    padding: 0.6rem 1.25rem;
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid var(--border-subtle);
    color: var(--text-muted);
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.2s ease;
}
.doc-tab:hover {
    color: var(--gold-light);
    border-color: var(--border-gold);
    background: rgba(229, 169, 60, 0.08);
}
.doc-tab.active {
    color: #07090c;
    background: linear-gradient(135deg, #e5a93c, #cf9128);
    border-color: var(--gold-primary);
    font-weight: 700;
}

.page-content-wrap {
    padding: 3.5rem 2rem 6rem;
    max-width: 960px;
    margin: 0 auto;
}
.document-paper {
    position: relative;
    background: #0d1118;
    border: 1px solid var(--border-gold);
    border-radius: 14px;
    padding: 3.5rem;
    box-shadow: 0 20px 50px rgba(0,0,0,0.7), 0 0 30px rgba(229, 169, 60, 0.05);
    overflow: hidden;
}
.document-watermark {
    position: absolute;
    top: 35%;
    left: 50%;
    transform: translate(-50%, -50%) rotate(-25deg);
    font-size: 8rem;
    font-family: 'Cinzel', serif;
    font-weight: 900;
    color: rgba(229, 169, 60, 0.02);
    pointer-events: none;
    letter-spacing: 0.6rem;
    user-select: none;
}

/* Document Body Styling */
.document-body {
    position: relative;
    z-index: 2;
    color: #cbd5e1;
    font-size: 1.06rem;
    line-height: 1.85;
}
.document-body h1 {
    font-size: 1.9rem;
    color: var(--gold-light);
    font-family: 'Cinzel', serif;
    margin-top: 1rem;
    margin-bottom: 1.25rem;
    border-bottom: 1px solid var(--border-gold);
    padding-bottom: 0.6rem;
}
.document-body h2 {
    color: var(--gold-primary);
    font-size: 1.45rem;
    font-family: 'Cinzel', serif;
    margin-top: 2.25rem;
    margin-bottom: 1rem;
    border-bottom: 1px solid rgba(229,169,60,0.25);
    padding-bottom: 0.5rem;
}
.document-body h3 {
    color: #ffffff;
    font-size: 1.2rem;
    margin-top: 1.75rem;
    margin-bottom: 0.75rem;
    font-family: 'Cinzel', serif;
}
.document-body p {
    margin-bottom: 1.35rem;
}
.document-body strong {
    color: #ffffff;
    font-weight: 600;
}
.document-body em {
    color: #e2e8f0;
}
.document-body ul, .document-body ol {
    padding-left: 1.75rem;
    margin-bottom: 1.5rem;
}
.document-body li {
    margin-bottom: 0.65rem;
}
.document-body blockquote {
    margin: 2rem 0;
    padding: 1.25rem 1.75rem;
    background: rgba(229, 169, 60, 0.06);
    border-left: 4px solid var(--gold-primary);
    border-radius: 0 8px 8px 0;
    color: #f1f5f9;
    font-style: italic;
    font-size: 1.05rem;
}
.document-body blockquote p:last-child {
    margin-bottom: 0;
}
.document-body hr {
    border: none;
    border-top: 1px solid rgba(229, 169, 60, 0.25);
    margin: 2.5rem 0;
}

/* Tables in Document */
.document-body table {
    width: 100%;
    border-collapse: collapse;
    margin: 2rem 0;
    background: rgba(10, 14, 20, 0.6);
    border: 1px solid var(--border-gold);
    border-radius: 8px;
    overflow: hidden;
}
.document-body th {
    background: rgba(229, 169, 60, 0.15);
    color: var(--gold-light);
    font-family: 'Cinzel', serif;
    padding: 0.85rem 1.25rem;
    text-align: left;
    font-size: 0.95rem;
    border-bottom: 1px solid var(--border-gold);
}
.document-body td {
    padding: 0.85rem 1.25rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    color: #e2e8f0;
}
.document-body tr:last-child td {
    border-bottom: none;
}
.document-body tr:hover td {
    background: rgba(229, 169, 60, 0.03);
}

.document-footer {
    margin-top: 3.5rem;
    padding-top: 2rem;
    border-top: 1px solid var(--border-subtle);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1.5rem;
    position: relative;
    z-index: 2;
}
.footer-seal {
    display: flex;
    align-items: center;
    gap: 1rem;
}
.seal-icon {
    font-size: 2.4rem;
    color: var(--gold-primary);
}
.footer-seal strong {
    display: block;
    color: var(--gold-light);
    font-family: 'Cinzel', serif;
    font-size: 1.05rem;
}
.footer-seal p {
    margin: 0;
    font-size: 0.82rem;
    color: var(--text-muted);
}

@media (max-width: 768px) {
    .page-title {
        font-size: 1.85rem;
    }
    .document-paper {
        padding: 2rem 1.25rem;
    }
    .document-footer {
        flex-direction: column;
        align-items: stretch;
        text-align: center;
    }
    .footer-seal {
        justify-content: center;
        text-align: left;
    }
    .action-cta .btn {
        width: 100%;
    }
}
</style>
@endsection
