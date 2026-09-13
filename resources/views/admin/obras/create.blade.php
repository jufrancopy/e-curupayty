@extends('layouts.admin')

@section('title', 'Registrar Nueva Obra')
@section('header-title', 'Incorporar Obra al Catálogo & Repositorio')

@section('content')
<div class="obra-form-page">
    <div class="mb-3">
        <a href="{{ route('admin.obras.index') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-arrow-left"></i> Volver a Obras
        </a>
    </div>

    <div class="admin-card">
        <form action="{{ route('admin.obras.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-grid">
                <!-- Col 1: Basic Info -->
                <div class="form-section">
                    <h3 class="section-heading"><i class="fas fa-info-circle"></i> Identificación de la Obra</h3>

                    <div class="form-group">
                        <label>Título de la Obra *</label>
                        <input type="text" name="titulo" class="form-control" value="{{ old('titulo') }}" required placeholder="Ej: Suite Mbyky: Primavera Barroca">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Nombre del Compositor *</label>
                            <input type="text" name="compositor_nombre" class="form-control" value="{{ old('compositor_nombre') }}" required placeholder="Ej: Maestro Julio Franco">
                        </div>
                        <div class="form-group">
                            <label>Género / Estilo *</label>
                            <select name="genero" class="form-control" required>
                                <option value="Barroco Guaraní">Barroco Guaraní</option>
                                <option value="Sinfónico">Sinfónico</option>
                                <option value="Guarania Sinfónica">Guarania Sinfónica</option>
                                <option value="Avanzada">Avanzada</option>
                                <option value="Música de Cámara">Música de Cámara</option>
                                <option value="Contemporáneo">Contemporáneo</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Instrumentación *</label>
                            <input type="text" name="instrumentacion" class="form-control" value="{{ old('instrumentacion') }}" required placeholder="Ej: Ensamble de Cuerdas (Violines, Violas, Chelos, Contrabajo) y Clave">
                        </div>
                        <div class="form-group">
                            <label>Duración Estimada</label>
                            <input type="text" name="duracion_estimada" class="form-control" value="{{ old('duracion_estimada') }}" placeholder="Ej: 3 min 45 seg">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Tonalidad</label>
                            <input type="text" name="tonalidad" class="form-control" value="{{ old('tonalidad') }}" placeholder="Ej: Re menor (Allegro Vivace)">
                        </div>
                        <div class="form-group">
                            <label>Tempo (BPM)</label>
                            <input type="number" name="tempo_bpm" class="form-control" value="{{ old('tempo_bpm', 120) }}">
                        </div>
                        <div class="form-group">
                            <label>Año de Composición</label>
                            <input type="number" name="ano_composicion" class="form-control" value="{{ old('ano_composicion', 2026) }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Descripción / Memoria Descriptiva</label>
                        <textarea name="descripcion" rows="5" class="form-control" placeholder="Contexto histórico, técnica compositiva, inspiración y relevancia artística..."></textarea>
                    </div>
                </div>

                <!-- Col 2: Legal & Files -->
                <div class="form-section">
                    <h3 class="section-heading"><i class="fas fa-balance-scale"></i> Régimen Legal y Archivos Digitales</h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Estado Registral DINAPI</label>
                            <select name="estado_dinapi" class="form-control">
                                <option value="tramite">En Trámite</option>
                                <option value="registrado">Registrado Oficialmente</option>
                                <option value="dominio_publico">Dominio Público</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nº de Registro DINAPI</label>
                            <input type="text" name="numero_registro_dinapi" class="form-control" placeholder="Ej: DINAPI-PY-2026-894">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Tipo de Licencia</label>
                        <select name="licencia_tipo" class="form-control">
                            <option value="Modelo Curupayty 40/25/10/25">Modelo Curupayty 40/25/10/25 (Autogestión)</option>
                            <option value="Creative Commons BY-NC-SA 4.0">Creative Commons BY-NC-SA 4.0</option>
                            <option value="Todos los derechos reservados">Todos los derechos reservados (Exclusivo)</option>
                        </select>
                    </div>

                    <hr class="divider">

                    <div class="form-group">
                        <label><i class="fas fa-file-pdf text-gold"></i> Partitura General (PDF)</label>
                        <input type="file" name="partitura_pdf" class="form-control" accept=".pdf">
                        <small class="form-hint">Para visualización en el reproductor interactivo.</small>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-music text-emerald"></i> Audio Grabado / Maqueta (MP3, WAV)</label>
                        <input type="file" name="audio" class="form-control" accept="audio/*">
                        <small class="form-hint">Audio para reproducción en el catálogo online.</small>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-archive text-blue"></i> Particellas por Instrumento (ZIP)</label>
                        <input type="file" name="particellas_zip" class="form-control" accept=".zip,.rar">
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-image"></i> Portada / Ilustración (JPG, PNG)</label>
                        <input type="file" name="portada" class="form-control" accept="image/*">
                    </div>

                    <div class="form-check-group mt-3">
                        <label class="checkbox-label">
                            <input type="checkbox" name="activo" value="1" checked>
                            <span>Publicar inmediatamente en el catálogo web accesible a la ciudadanía</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-actions mt-4">
                <button type="submit" class="btn btn-gold">
                    <i class="fas fa-cloud-upload-alt"></i> Registrar y Guardar Obra
                </button>
                <a href="{{ route('admin.obras.index') }}" class="btn btn-outline">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<style>
.form-grid {
    display: grid;
    grid-template-columns: 1.2fr 1fr;
    gap: 2.5rem;
}
.section-heading {
    font-size: 1.05rem;
    color: var(--admin-gold);
    border-bottom: 1px solid var(--admin-border);
    padding-bottom: 0.5rem;
    margin-bottom: 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.form-group {
    margin-bottom: 1.1rem;
}
.form-group label {
    display: block;
    font-size: 0.82rem;
    color: var(--admin-muted);
    margin-bottom: 0.35rem;
    font-weight: 500;
}
.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 1rem;
}
.form-control {
    width: 100%;
    background: #090c12;
    border: 1px solid var(--admin-border);
    border-radius: 6px;
    padding: 0.6rem 0.85rem;
    color: #fff;
    font-size: 0.88rem;
    outline: none;
}
.form-control:focus {
    border-color: var(--admin-gold);
}
.form-hint {
    display: block;
    font-size: 0.72rem;
    color: var(--admin-muted);
    margin-top: 0.25rem;
}
.checkbox-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: #cbd5e1;
    cursor: pointer;
}
.form-actions {
    display: flex;
    gap: 1rem;
    border-top: 1px solid var(--admin-border);
    padding-top: 1.5rem;
}
@media (max-width: 900px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection
