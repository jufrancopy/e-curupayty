@extends('layouts.admin')

@section('title', 'Editar Obra — ' . $obra->titulo)
@section('header-title', 'Modificar Obra: ' . $obra->titulo)

@section('content')
<div class="obra-form-page">
    <div class="mb-3">
        <a href="{{ route('admin.obras.index') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-arrow-left"></i> Volver a Obras
        </a>
    </div>

    <div class="admin-card">
        <form action="{{ route('admin.obras.update', $obra->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <!-- Col 1: Basic Info -->
                <div class="form-section">
                    <h3 class="section-heading"><i class="fas fa-info-circle"></i> Identificación de la Obra</h3>

                    <div class="form-group">
                        <label>Título de la Obra *</label>
                        <input type="text" name="titulo" class="form-control" value="{{ old('titulo', $obra->titulo) }}" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Nombre del Compositor *</label>
                            <input type="text" name="compositor_nombre" class="form-control" value="{{ old('compositor_nombre', $obra->compositor_nombre) }}" required>
                        </div>
                        <div class="form-group">
                            <label>Género / Estilo *</label>
                            <select name="genero" class="form-control" required>
                                <option value="Barroco Guaraní" {{ $obra->genero == 'Barroco Guaraní' ? 'selected' : '' }}>Barroco Guaraní</option>
                                <option value="Sinfónico" {{ $obra->genero == 'Sinfónico' ? 'selected' : '' }}>Sinfónico</option>
                                <option value="Guarania Sinfónica" {{ $obra->genero == 'Guarania Sinfónica' ? 'selected' : '' }}>Guarania Sinfónica</option>
                                <option value="Avanzada" {{ $obra->genero == 'Avanzada' ? 'selected' : '' }}>Avanzada</option>
                                <option value="Música de Cámara" {{ $obra->genero == 'Música de Cámara' ? 'selected' : '' }}>Música de Cámara</option>
                                <option value="Contemporáneo" {{ $obra->genero == 'Contemporáneo' ? 'selected' : '' }}>Contemporáneo</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Instrumentación *</label>
                            <input type="text" name="instrumentacion" class="form-control" value="{{ old('instrumentacion', $obra->instrumentacion) }}" required>
                        </div>
                        <div class="form-group">
                            <label>Duración Estimada</label>
                            <input type="text" name="duracion_estimada" class="form-control" value="{{ old('duracion_estimada', $obra->duracion_estimada) }}">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Tonalidad</label>
                            <input type="text" name="tonalidad" class="form-control" value="{{ old('tonalidad', $obra->tonalidad) }}">
                        </div>
                        <div class="form-group">
                            <label>Tempo (BPM)</label>
                            <input type="number" name="tempo_bpm" class="form-control" value="{{ old('tempo_bpm', $obra->tempo_bpm) }}">
                        </div>
                        <div class="form-group">
                            <label>Año de Composición</label>
                            <input type="number" name="ano_composicion" class="form-control" value="{{ old('ano_composicion', $obra->ano_composicion) }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Descripción / Memoria Descriptiva</label>
                        <textarea name="descripcion" rows="5" class="form-control">{{ old('descripcion', $obra->descripcion) }}</textarea>
                    </div>
                </div>

                <!-- Col 2: Legal & Files -->
                <div class="form-section">
                    <h3 class="section-heading"><i class="fas fa-balance-scale"></i> Régimen Legal y Archivos Digitales</h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Estado Registral DINAPI</label>
                            <select name="estado_dinapi" class="form-control">
                                <option value="tramite" {{ $obra->estado_dinapi == 'tramite' ? 'selected' : '' }}>En Trámite</option>
                                <option value="registrado" {{ $obra->estado_dinapi == 'registrado' ? 'selected' : '' }}>Registrado Oficialmente</option>
                                <option value="dominio_publico" {{ $obra->estado_dinapi == 'dominio_publico' ? 'selected' : '' }}>Dominio Público</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nº de Registro DINAPI</label>
                            <input type="text" name="numero_registro_dinapi" class="form-control" value="{{ old('numero_registro_dinapi', $obra->numero_registro_dinapi) }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Tipo de Licencia</label>
                        <select name="licencia_tipo" class="form-control">
                            <option value="Modelo Curupayty 40/25/10/25" {{ $obra->licencia_tipo == 'Modelo Curupayty 40/25/10/25' ? 'selected' : '' }}>Modelo Curupayty 40/25/10/25 (Autogestión)</option>
                            <option value="Creative Commons BY-NC-SA 4.0" {{ $obra->licencia_tipo == 'Creative Commons BY-NC-SA 4.0' ? 'selected' : '' }}>Creative Commons BY-NC-SA 4.0</option>
                            <option value="Todos los derechos reservados" {{ $obra->licencia_tipo == 'Todos los derechos reservados' ? 'selected' : '' }}>Todos los derechos reservados (Exclusivo)</option>
                        </select>
                    </div>

                    <hr class="divider">

                    <div class="form-group">
                        <label><i class="fas fa-file-pdf text-gold"></i> Actualizar Partitura General (PDF)</label>
                        @if($obra->partitura_pdf_path)
                            <p class="current-file"><i class="fas fa-check"></i> Archivo actual: <a href="{{ asset($obra->partitura_pdf_path) }}" target="_blank">Ver PDF</a></p>
                        @endif
                        <input type="file" name="partitura_pdf" class="form-control" accept=".pdf">
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-music text-emerald"></i> Actualizar Audio Grabado (MP3, WAV)</label>
                        @if($obra->audio_path)
                            <p class="current-file"><i class="fas fa-check"></i> Audio actual registrado: <a href="{{ asset($obra->audio_path) }}" target="_blank">Escuchar</a></p>
                        @endif
                        <input type="file" name="audio" class="form-control" accept="audio/*">
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-archive text-blue"></i> Actualizar Particellas (ZIP)</label>
                        @if($obra->particellas_zip_path)
                            <p class="current-file"><i class="fas fa-check"></i> Archivo actual registrado</p>
                        @endif
                        <input type="file" name="particellas_zip" class="form-control" accept=".zip,.rar">
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-image"></i> Actualizar Portada (JPG, PNG)</label>
                        <input type="file" name="portada" class="form-control" accept="image/*">
                    </div>

                    <div class="form-check-group mt-3">
                        <label class="checkbox-label">
                            <input type="checkbox" name="activo" value="1" {{ $obra->activo ? 'checked' : '' }}>
                            <span>Publicado en el catálogo web</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-actions mt-4">
                <button type="submit" class="btn btn-gold">
                    <i class="fas fa-save"></i> Guardar Cambios
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
.current-file {
    font-size: 0.75rem;
    color: #34d399;
    margin-bottom: 0.3rem;
}
.current-file a {
    color: var(--admin-gold);
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
