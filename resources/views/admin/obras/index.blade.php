@extends('layouts.admin')

@section('title', 'Gestión de Obras y Partituras')
@section('header-title', 'Catálogo de Obras & Propiedad Intelectual')

@section('content')
<div class="obras-admin-page">
    <div class="page-top-actions">
        <div>
            <p class="section-desc">Administración de partituras digitales, archivos de audio y estado registral ante DINAPI/APA/AIE.</p>
        </div>
        <div>
            <a href="{{ route('admin.obras.create') }}" class="btn btn-gold">
                <i class="fas fa-plus"></i> Registrar Nueva Obra
            </a>
        </div>
    </div>

    <div class="admin-card">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título de la Obra</th>
                        <th>Compositor</th>
                        <th>Género</th>
                        <th>Instrumentación</th>
                        <th>DINAPI / Registro</th>
                        <th>Archivos</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($obras as $obra)
                        <tr>
                            <td><span class="code-tag">#{{ $obra->id }}</span></td>
                            <td>
                                <strong>{{ $obra->titulo }}</strong>
                                <div class="sub-text">{{ $obra->duracion_estimada ?? 'Duración N/D' }}</div>
                            </td>
                            <td>{{ $obra->compositor_nombre }}</td>
                            <td><span class="badge-subtle">{{ $obra->genero }}</span></td>
                            <td><span class="sub-text">{{ Str::limit($obra->instrumentacion, 30) }}</span></td>
                            <td>
                                @if($obra->estado_dinapi == 'registrado')
                                    <span class="badge badge-success"><i class="fas fa-shield-alt"></i> {{ $obra->numero_registro_dinapi ?? 'Registrado' }}</span>
                                @else
                                    <span class="badge badge-warning">En Trámite</span>
                                @endif
                            </td>
                            <td>
                                <div class="file-indicators">
                                    @if($obra->partitura_pdf_path) <i class="fas fa-file-pdf text-gold" title="Partitura PDF cargada"></i> @endif
                                    @if($obra->audio_path) <i class="fas fa-music text-emerald" title="Audio MP3 cargado"></i> @endif
                                    @if($obra->midi_path) <i class="fas fa-compact-disc text-blue" title="MIDI cargado"></i> @endif
                                </div>
                            </td>
                            <td>
                                @if($obra->activo)
                                    <span class="badge badge-success">Pública</span>
                                @else
                                    <span class="badge badge-subtle">Borrador</span>
                                @endif
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('catalogo.show', $obra->slug) }}" target="_blank" class="btn btn-outline btn-xs" title="Ver en Catálogo Público">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                    <a href="{{ route('admin.obras.edit', $obra->id) }}" class="btn btn-outline btn-xs" title="Editar Obra">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.obras.destroy', $obra->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar esta obra del catálogo?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline btn-xs btn-danger" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">No hay obras registradas en el catálogo.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $obras->links() }}
        </div>
    </div>
</div>

<style>
.badge-subtle {
    background: rgba(255,255,255,0.06);
    color: #cbd5e1;
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
}
.file-indicators {
    display: flex;
    gap: 0.5rem;
    font-size: 0.95rem;
}
.text-gold { color: var(--admin-gold); }
.text-emerald { color: #34d399; }
.text-blue { color: #60a5fa; }
.btn-danger:hover {
    border-color: #ef4444;
    color: #ef4444;
}
</style>
@endsection
