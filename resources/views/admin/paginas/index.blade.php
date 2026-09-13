@extends('layouts.admin')

@section('title', 'Gestión de Páginas Institucionales')
@section('header-title', 'Portal Institucional & CMS de Contenidos')

@section('content')
<div class="paginas-admin-page">
    <div class="page-top-actions">
        <div>
            <p class="section-desc">Publicación y edición de los documentos rectores del Ensamble Curupayty (Manifiesto, Estatutos, Calendario de Asamblea).</p>
        </div>
    </div>

    <div class="admin-card">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Identificador</th>
                        <th>Título del Documento</th>
                        <th>Subtítulo / Bajada</th>
                        <th>Estado</th>
                        <th>Última Actualización</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($paginas as $pag)
                        <tr>
                            <td><span class="code-tag">{{ $pag->slug }}</span></td>
                            <td>
                                <strong>{{ $pag->titulo }}</strong>
                            </td>
                            <td><span class="sub-text">{{ Str::limit($pag->subtitulo, 50) }}</span></td>
                            <td>
                                @if($pag->activo)
                                    <span class="badge badge-success">Público</span>
                                @else
                                    <span class="badge badge-warning">Borrador</span>
                                @endif
                            </td>
                            <td>{{ $pag->updated_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('pagina.show', $pag->slug) }}" target="_blank" class="btn btn-outline btn-xs" title="Ver en Sitio Web">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                    <a href="{{ route('admin.paginas.edit', $pag->id) }}" class="btn btn-gold btn-xs" title="Editar Contenido">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
