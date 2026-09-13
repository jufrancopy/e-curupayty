@extends('layouts.admin')

@section('title', 'Editar Documento — ' . $pagina->titulo)
@section('header-title', 'Editar Documento: ' . $pagina->titulo)

@section('content')
<div class="pagina-edit-page">
    <div class="mb-3">
        <a href="{{ route('admin.paginas.index') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-arrow-left"></i> Volver a Documentos
        </a>
    </div>

    <div class="admin-card">
        <form action="{{ route('admin.paginas.update', $pagina->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Título del Documento Institucional *</label>
                <input type="text" name="titulo" class="form-control" value="{{ old('titulo', $pagina->titulo) }}" required>
            </div>

            <div class="form-group">
                <label>Subtítulo o Resumen Breve</label>
                <input type="text" name="subtitulo" class="form-control" value="{{ old('subtitulo', $pagina->subtitulo) }}">
            </div>

            <div class="form-group">
                <label>Cuerpo del Documento (HTML / Texto Enriquecido) *</label>
                <textarea name="contenido" rows="16" class="form-control code-editor" required>{{ old('contenido', $pagina->contenido) }}</textarea>
                <small class="form-hint">Podés usar etiquetas HTML estándar como &lt;h2&gt;, &lt;p&gt;, &lt;ul&gt;, &lt;li&gt;, &lt;strong&gt;, etc.</small>
            </div>

            <div class="form-check-group mb-4">
                <label class="checkbox-label">
                    <input type="checkbox" name="activo" value="1" {{ $pagina->activo ? 'checked' : '' }}>
                    <span>Publicado y visible en el portal institucional</span>
                </label>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-gold">
                    <i class="fas fa-save"></i> Guardar Modificaciones
                </button>
                <a href="{{ route('admin.paginas.index') }}" class="btn btn-outline">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<style>
.form-group {
    margin-bottom: 1.25rem;
}
.form-group label {
    display: block;
    font-size: 0.82rem;
    color: var(--admin-muted);
    margin-bottom: 0.35rem;
    font-weight: 500;
}
.form-control {
    width: 100%;
    background: #090c12;
    border: 1px solid var(--admin-border);
    border-radius: 6px;
    padding: 0.65rem 0.85rem;
    color: #fff;
    font-size: 0.88rem;
    outline: none;
}
.form-control:focus {
    border-color: var(--admin-gold);
}
.code-editor {
    font-family: 'Courier New', Courier, monospace;
    font-size: 0.9rem;
    line-height: 1.6;
}
.form-hint {
    display: block;
    font-size: 0.75rem;
    color: var(--admin-muted);
    margin-top: 0.35rem;
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
.mb-4 { margin-bottom: 1.5rem; }
</style>
@endsection
