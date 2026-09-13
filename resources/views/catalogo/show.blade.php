@extends('layouts.app')

@section('title', $obra->titulo . ' — Partitura Oficial Ensamble Curupayty')

@section('content')
<div class="obra-detail-page">
    <div class="obra-hero">
        <div class="container">
            <div class="obra-hero-content">
                <a href="{{ route('catalogo.index') }}" class="back-link">
                    <i class="fas fa-arrow-left"></i> Volver al Catálogo
                </a>
                
                <div class="obra-header-tags">
                    <span class="genre-tag">{{ $obra->genero }}</span>
                    @if($obra->estado_dinapi == 'registrado')
                        <span class="status-tag registered"><i class="fas fa-certificate"></i> Registrado DINAPI</span>
                    @endif
                    <span class="license-tag"><i class="fas fa-creative-commons"></i> {{ $obra->licencia_tipo }}</span>
                </div>

                <h1 class="obra-detail-title">{{ $obra->titulo }}</h1>
                <p class="obra-detail-composer">
                    Compuesta por <strong>{{ $obra->compositor_nombre }}</strong>
                    @if($obra->ano_composicion) · Año {{ $obra->ano_composicion }} @endif
                    @if($obra->duracion_estimada) · Duración: {{ $obra->duracion_estimada }} @endif
                </p>

                <div class="obra-audio-master">
                    @if($obra->audio_path)
                        <div class="player-container">
                            <span class="player-label"><i class="fas fa-play-circle"></i> Interpretación Oficial del Ensamble:</span>
                            <audio controls class="full-audio-player">
                                <source src="{{ asset($obra->audio_path) }}" type="audio/mpeg">
                                Tu navegador no soporta el reproductor de audio.
                            </audio>
                        </div>
                    @else
                        <p class="text-muted"><i class="fas fa-info-circle"></i> Grabación de audio en proceso de masterización en estudio.</p>
                    @endif
                </div>

                <div class="download-bar">
                    @if($obra->partitura_pdf_path)
                        <a href="{{ asset($obra->partitura_pdf_path) }}" class="btn btn-gold" download>
                            <i class="fas fa-file-pdf"></i> Descargar Partitura General (PDF)
                        </a>
                    @endif
                    @if($obra->particellas_zip_path)
                        <a href="{{ asset($obra->particellas_zip_path) }}" class="btn btn-outline" download>
                            <i class="fas fa-archive"></i> Descargar Particellas de Instrumentos (ZIP)
                        </a>
                    @endif
                    @if($obra->midi_path)
                        <a href="{{ asset($obra->midi_path) }}" class="btn btn-outline" download>
                            <i class="fas fa-compact-disc"></i> Archivo MIDI
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="container obra-body-grid">
        <div class="obra-main-col">
            <!-- Sheet Music Interactive Viewer -->
            <div class="card viewer-card">
                <div class="card-header-flex">
                    <h2><i class="fas fa-music"></i> Visor de Partitura Digital</h2>
                    @if($obra->partitura_pdf_path)
                        <a href="{{ asset($obra->partitura_pdf_path) }}" target="_blank" class="btn btn-outline btn-sm">
                            <i class="fas fa-external-link-alt"></i> Pantalla Completa
                        </a>
                    @endif
                </div>

                @if($obra->partitura_pdf_path)
                    <div class="pdf-viewer-wrap">
                        <iframe src="{{ asset($obra->partitura_pdf_path) }}#toolbar=1&navpanes=0" class="pdf-frame" title="Partitura Digital"></iframe>
                    </div>
                @else
                    <div class="viewer-placeholder">
                        <i class="fas fa-file-invoice empty-icon"></i>
                        <p>La partitura digital interactiva se encuentra en revisión tipográfica por la comisión técnica.</p>
                    </div>
                @endif
            </div>

            <!-- Context & Description -->
            <div class="card info-card">
                <h2><i class="fas fa-book-open"></i> Reseña y Memoria de la Obra</h2>
                <div class="obra-desc-text">
                    {!! nl2br(e($obra->descripcion)) !!}
                </div>
            </div>
        </div>

        <div class="obra-sidebar-col">
            <!-- Technical Specs -->
            <div class="card side-spec-card">
                <h3>Ficha Técnica</h3>
                <ul class="spec-list">
                    <li>
                        <strong>Instrumentación:</strong>
                        <span>{{ $obra->instrumentacion ?? 'Ensamble de Cuerdas y Vientos' }}</span>
                    </li>
                    <li>
                        <strong>Tonalidad:</strong>
                        <span>{{ $obra->tonalidad ?? 'Re menor / Re Mayor' }}</span>
                    </li>
                    <li>
                        <strong>Tempo / Compás:</strong>
                        <span>{{ $obra->tempo_bpm ? $obra->tempo_bpm . ' BPM' : 'Allegro Vivace' }}</span>
                    </li>
                    <li>
                        <strong>Registro DINAPI:</strong>
                        <span>{{ $obra->numero_registro_dinapi ?? 'En trámite oficial' }}</span>
                    </li>
                    <li>
                        <strong>Gestión Colectiva:</strong>
                        <span>Sometido a fiscalización APA / AIE bajo transparencia</span>
                    </li>
                </ul>
            </div>

            <!-- 40/25/10/25 Model Card -->
            <div class="card model-breakdown-card">
                <h3><i class="fas fa-coins"></i> Modelo Económico 40/25/10/25</h3>
                <p class="model-intro">Cada ejecución y comercialización de esta obra se liquida equitativamente:</p>
                
                <div class="breakdown-bar-wrap">
                    <div class="breakdown-item item-40">
                        <span class="pct">40%</span>
                        <span class="label">Músicos e Intérpretes en Escenario</span>
                    </div>
                    <div class="breakdown-item item-25a">
                        <span class="pct">25%</span>
                        <span class="label">Compositor & Derechos de Autor</span>
                    </div>
                    <div class="breakdown-item item-10">
                        <span class="pct">10%</span>
                        <span class="label">Fondo de Retiro y Previsión Social</span>
                    </div>
                    <div class="breakdown-item item-25b">
                        <span class="pct">25%</span>
                        <span class="label">Fondo Operativo y Giras Curupayty</span>
                    </div>
                </div>
            </div>

            <!-- Adherence CTA -->
            <div class="card adherence-box">
                <i class="fas fa-signature adherence-icon"></i>
                <h4>¿Querés formar parte del movimiento?</h4>
                <p>Sumá tu firma al Acta Fundacional y participá en las decisiones del Ensamble.</p>
                <a href="{{ route('landing') }}#firma" class="btn btn-gold btn-sm btn-block">
                    Firmar el Acta
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.obra-hero {
    background: radial-gradient(circle at 50% 0%, rgba(229,169,60,0.18), transparent 70%), var(--bg-card);
    border-bottom: 1px solid var(--border-gold);
    padding: 3.5rem 0;
}
.obra-hero .container {
    max-width: 1200px;
    margin-left: auto;
    margin-right: auto;
    padding-left: 2.5rem;
    padding-right: 2.5rem;
}
.back-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-muted);
    font-size: 0.88rem;
    margin-bottom: 1.25rem;
    text-decoration: none;
    transition: color 0.2s;
}
.back-link:hover {
    color: var(--gold-primary);
}
.obra-header-tags {
    display: flex;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
    flex-wrap: wrap;
}
.genre-tag, .status-tag, .license-tag {
    font-size: 0.75rem;
    padding: 0.25rem 0.65rem;
    border-radius: 4px;
    font-weight: 500;
}
.genre-tag {
    background: rgba(229, 169, 60, 0.15);
    color: var(--gold-primary);
    border: 1px solid var(--border-gold);
}
.status-tag.registered {
    background: rgba(16, 185, 129, 0.15);
    color: #34d399;
    border: 1px solid rgba(16, 185, 129, 0.3);
}
.license-tag {
    background: rgba(255, 255, 255, 0.05);
    color: var(--text-muted);
    border: 1px solid var(--border-subtle);
}
.obra-detail-title {
    font-size: 2.4rem;
    margin: 0.5rem 0;
    color: var(--text-light);
}
.obra-detail-composer {
    color: var(--gold-light);
    font-size: 1.1rem;
    margin-bottom: 1.75rem;
}
.obra-audio-master {
    background: rgba(7, 9, 12, 0.7);
    border: 1px solid var(--border-gold);
    border-radius: 8px;
    padding: 1.25rem;
    margin-bottom: 1.75rem;
    max-width: 700px;
}
.player-container {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}
.player-label {
    font-size: 0.88rem;
    color: var(--gold-light);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.full-audio-player {
    width: 100%;
    height: 40px;
    border-radius: 6px;
}
.download-bar {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}
.obra-body-grid {
    max-width: 1200px;
    margin-left: auto;
    margin-right: auto;
    padding: 3.5rem 2.5rem 6rem;
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 2.5rem;
}
.card {
    background: var(--bg-card);
    border: 1px solid var(--border-subtle);
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 2rem;
}
.card-header-flex {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}
.card h2 {
    font-size: 1.35rem;
    color: var(--gold-light);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.6rem;
}
.card h3 {
    font-size: 1.15rem;
    color: var(--gold-light);
    margin-top: 0;
    margin-bottom: 1rem;
    font-family: 'Cinzel', serif;
}
.pdf-viewer-wrap {
    width: 100%;
    height: 600px;
    background: #000;
    border: 1px solid var(--border-subtle);
    border-radius: 8px;
    overflow: hidden;
}
.pdf-frame {
    width: 100%;
    height: 100%;
    border: none;
}
.viewer-placeholder {
    text-align: center;
    padding: 4rem 1.5rem;
    color: var(--text-muted);
}
.obra-desc-text {
    color: #cbd5e1;
    line-height: 1.8;
    font-size: 1rem;
}
.spec-list {
    list-style: none;
    padding: 0;
    margin: 0;
}
.spec-list li {
    padding: 0.75rem 0;
    border-bottom: 1px solid rgba(255,255,255,0.05);
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}
.spec-list li:last-child {
    border-bottom: none;
}
.spec-list strong {
    color: var(--text-muted);
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
.spec-list span {
    color: var(--text-light);
    font-size: 0.95rem;
}
.model-intro {
    font-size: 0.85rem;
    color: var(--text-muted);
    margin-bottom: 1rem;
}
.breakdown-bar-wrap {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}
.breakdown-item {
    background: rgba(255,255,255,0.03);
    border-radius: 6px;
    padding: 0.6rem 0.85rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    border-left: 3px solid var(--gold-primary);
}
.breakdown-item.item-40 { border-color: #e5a93c; }
.breakdown-item.item-25a { border-color: #3b82f6; }
.breakdown-item.item-10 { border-color: #10b981; }
.breakdown-item.item-25b { border-color: #a855f7; }
.breakdown-item .pct {
    font-weight: 700;
    color: var(--gold-light);
    font-size: 1rem;
    min-width: 38px;
}
.breakdown-item .label {
    font-size: 0.8rem;
    color: #cbd5e1;
}
.adherence-box {
    text-align: center;
    background: radial-gradient(circle at top, rgba(229,169,60,0.1), transparent), var(--bg-card);
    border-color: var(--border-gold);
}
.adherence-icon {
    font-size: 2.2rem;
    color: var(--gold-primary);
    margin-bottom: 0.75rem;
}
.adherence-box h4 {
    font-size: 1.1rem;
    margin: 0 0 0.5rem;
    color: var(--text-light);
}
.adherence-box p {
    font-size: 0.82rem;
    color: var(--text-muted);
    margin-bottom: 1.25rem;
}
.btn-block {
    display: block;
    width: 100%;
}
@media (min-width: 1440px) {
    .obra-hero .container,
    .obra-body-grid {
        max-width: 1240px;
        padding-left: 3rem;
        padding-right: 3rem;
    }
}
@media (max-width: 992px) {
    .obra-hero .container {
        padding-left: 2rem;
        padding-right: 2rem;
    }
    .obra-body-grid {
        grid-template-columns: 1fr;
        padding: 2.5rem 2rem 5rem;
        gap: 2rem;
    }
}
@media (max-width: 768px) {
    .obra-hero {
        padding: 2.5rem 0 2rem;
    }
    .obra-hero .container {
        padding-left: 1.25rem;
        padding-right: 1.25rem;
    }
    .obra-body-grid {
        padding: 2rem 1.25rem 4rem;
    }
}
</style>
@endsection
