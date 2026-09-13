@extends('layouts.app')

@section('title', 'Catálogo de Obras & Partituras — Ensamble Curupayty')

@section('content')
<div class="catalog-page">
    <div class="catalog-header">
        <div class="container">
            <span class="badge-tag">Patrimonio & Creación Viva</span>
            <h1 class="page-title">Catálogo Oficial de Obras</h1>
            <p class="page-subtitle">
                Acceso democrático a las partituras digitales, ensambles y registros sonoros del nuevo sinfonismo paraguayo bajo el modelo <strong>40/25/10/25</strong>.
            </p>

            <form action="{{ route('catalogo.index') }}" method="GET" class="catalog-search-bar">
                <div class="search-input-group">
                    <i class="fas fa-search"></i>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar por título, compositor o instrumento...">
                </div>
                <div class="filter-group">
                    <select name="genero" onchange="this.form.submit()">
                        <option value="">Todos los géneros</option>
                        <option value="Sinfónico" {{ request('genero') == 'Sinfónico' ? 'selected' : '' }}>Sinfónico</option>
                        <option value="Barroco Guaraní" {{ request('genero') == 'Barroco Guaraní' ? 'selected' : '' }}>Barroco Guaraní</option>
                        <option value="Guarania Sinfónica" {{ request('genero') == 'Guarania Sinfónica' ? 'selected' : '' }}>Guarania Sinfónica</option>
                        <option value="Avanzada" {{ request('genero') == 'Avanzada' ? 'selected' : '' }}>Avanzada</option>
                        <option value="Música de Cámara" {{ request('genero') == 'Música de Cámara' ? 'selected' : '' }}>Música de Cámara</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-gold btn-sm">Filtrar</button>
            </form>
        </div>
    </div>

    <div class="container catalog-grid-wrap">
        @if($obras->count() > 0)
            <div class="obras-grid">
                @foreach($obras as $obra)
                    <div class="obra-card">
                        <div class="card-cover">
                            @if($obra->portada_path)
                                <img src="{{ asset($obra->portada_path) }}" alt="{{ $obra->titulo }}">
                            @else
                                <div class="cover-placeholder">
                                    <i class="fas fa-music"></i>
                                    <span>Partitura Oficial</span>
                                </div>
                            @endif
                            <span class="genre-badge">{{ $obra->genero }}</span>
                        </div>

                        <div class="card-content">
                            <h3 class="obra-title">
                                <a href="{{ route('catalogo.show', $obra->slug) }}">{{ $obra->titulo }}</a>
                            </h3>
                            <div class="obra-author">
                                <i class="fas fa-feather-alt"></i> {{ $obra->compositor_nombre }}
                            </div>
                            <div class="obra-instruments">
                                <i class="fas fa-layer-group"></i> {{ Str::limit($obra->instrumentacion, 45) }}
                            </div>
                            <p class="obra-desc">
                                {{ Str::limit($obra->descripcion, 90) }}
                            </p>

                            @if($obra->audio_path)
                                <div class="audio-mini-player">
                                    <audio controls preload="none">
                                        <source src="{{ asset($obra->audio_path) }}" type="audio/mpeg">
                                        Tu navegador no soporta el reproductor de audio.
                                    </audio>
                                </div>
                            @endif

                            <div class="card-meta-tags">
                                <span class="license-tag"><i class="fas fa-balance-scale"></i> {{ $obra->licencia_tipo }}</span>
                                @if($obra->estado_dinapi == 'registrado')
                                    <span class="dinapi-tag" title="Registrado en DINAPI"><i class="fas fa-shield-alt"></i> DINAPI</span>
                                @endif
                            </div>

                            <div class="card-actions">
                                <a href="{{ route('catalogo.show', $obra->slug) }}" class="btn btn-outline btn-sm">
                                    <i class="fas fa-eye"></i> Ver Partitura
                                </a>
                                @if($obra->partitura_pdf_path)
                                    <a href="{{ asset($obra->partitura_pdf_path) }}" target="_blank" class="btn btn-gold btn-sm" download>
                                        <i class="fas fa-download"></i> PDF
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pagination-wrap">
                {{ $obras->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-music empty-icon"></i>
                <h3>No se encontraron obras disponibles</h3>
                <p>Intenta con otros términos de búsqueda o vuelve a consultar nuestro catálogo más tarde.</p>
                <a href="{{ route('catalogo.index') }}" class="btn btn-gold btn-sm">Ver todas las obras</a>
            </div>
        @endif
    </div>
</div>

<style>
.catalog-header {
    background: radial-gradient(circle at 50% 0%, rgba(229,169,60,0.12), transparent 70%), var(--bg-card);
    border-bottom: 1px solid var(--border-gold);
    padding: 4rem 2rem 3.5rem;
    text-align: center;
}
.catalog-header .container {
    max-width: 960px;
    margin: 0 auto;
    padding: 0 1.5rem;
}
.catalog-search-bar {
    display: flex;
    max-width: 750px;
    margin: 2rem auto 0;
    gap: 0.75rem;
    background: rgba(10, 14, 20, 0.8);
    border: 1px solid var(--border-gold);
    border-radius: 8px;
    padding: 0.5rem;
}
.search-input-group {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding-left: 0.75rem;
    color: var(--text-muted);
}
.search-input-group input {
    width: 100%;
    background: transparent;
    border: none;
    outline: none;
    color: var(--text-light);
    font-size: 0.95rem;
}
.filter-group select {
    background: #161c28;
    border: 1px solid var(--border-subtle);
    border-radius: 6px;
    color: var(--text-light);
    padding: 0.5rem 0.75rem;
    font-size: 0.88rem;
    outline: none;
}
.catalog-grid-wrap {
    padding: 3.5rem 2.5rem 6rem;
    max-width: 1200px;
    margin-left: auto;
    margin-right: auto;
}
@media (min-width: 1440px) {
    .catalog-grid-wrap {
        max-width: 1240px;
        padding-left: 3rem;
        padding-right: 3rem;
    }
}
@media (max-width: 992px) {
    .catalog-grid-wrap {
        padding: 2.5rem 2rem 5rem;
    }
}
@media (max-width: 768px) {
    .catalog-grid-wrap {
        padding: 2rem 1.25rem 4rem;
    }
}
.obras-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 2rem;
}
.obra-card {
    background: var(--bg-card);
    border: 1px solid var(--border-subtle);
    border-radius: 12px;
    overflow: hidden;
    transition: transform 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease;
    display: flex;
    flex-direction: column;
}
.obra-card:hover {
    transform: translateY(-5px);
    border-color: var(--gold-primary);
    box-shadow: 0 10px 25px rgba(0,0,0,0.5);
}
.card-cover {
    position: relative;
    height: 180px;
    background: linear-gradient(135deg, #121824 0%, #1c2436 100%);
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
}
.card-cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.cover-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    color: var(--gold-primary);
    opacity: 0.8;
}
.cover-placeholder i {
    font-size: 2.8rem;
}
.cover-placeholder span {
    font-size: 0.8rem;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--text-muted);
}
.genre-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: rgba(7, 9, 12, 0.85);
    border: 1px solid var(--border-gold);
    color: var(--gold-light);
    font-size: 0.72rem;
    padding: 0.25rem 0.6rem;
    border-radius: 20px;
    backdrop-filter: blur(5px);
}
.card-content {
    padding: 1.5rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}
.obra-title {
    font-size: 1.25rem;
    margin: 0 0 0.5rem;
}
.obra-title a {
    color: var(--text-light);
    text-decoration: none;
    transition: color 0.2s;
}
.obra-title a:hover {
    color: var(--gold-primary);
}
.obra-author {
    color: var(--gold-light);
    font-size: 0.9rem;
    margin-bottom: 0.4rem;
    display: flex;
    align-items: center;
    gap: 0.4rem;
}
.obra-instruments {
    color: var(--text-muted);
    font-size: 0.82rem;
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.4rem;
}
.obra-desc {
    color: #94a3b8;
    font-size: 0.88rem;
    line-height: 1.5;
    margin-bottom: 1rem;
    flex: 1;
}
.audio-mini-player {
    margin-bottom: 1rem;
}
.audio-mini-player audio {
    width: 100%;
    height: 36px;
    border-radius: 6px;
}
.card-meta-tags {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1.25rem;
    font-size: 0.75rem;
}
.license-tag {
    background: rgba(255,255,255,0.05);
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
    color: var(--text-muted);
}
.dinapi-tag {
    background: rgba(16, 185, 129, 0.15);
    color: #34d399;
    border: 1px solid rgba(16, 185, 129, 0.3);
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
}
.card-actions {
    display: flex;
    gap: 0.5rem;
    margin-top: auto;
}
.card-actions .btn {
    flex: 1;
}
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: var(--text-muted);
}
.empty-icon {
    font-size: 3.5rem;
    color: var(--gold-primary);
    opacity: 0.4;
    margin-bottom: 1rem;
}
@media (max-width: 768px) {
    .catalog-search-bar {
        flex-direction: column;
    }
}
</style>
@endsection
