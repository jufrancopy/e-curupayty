@extends('layouts.admin')

@section('title', 'Gestión de Firmantes del Acta Fundacional')
@section('header-title', 'Padrón de Firmantes del Acta Fundacional')

@section('content')
<div class="firmantes-page">
    <div class="page-top-actions">
        <div>
            <p class="section-desc">Auditoría, validación legal y control del padrón fundacional del Ensamble Curupayty · Atypu.</p>
        </div>
        <div class="action-buttons">
            <a href="{{ route('admin.firmantes.export') }}" class="btn btn-outline">
                <i class="fas fa-file-csv"></i> Descargar Padrón CSV
            </a>
            <a href="{{ route('admin.firmantes.acta_oficial') }}" target="_blank" class="btn btn-gold">
                <i class="fas fa-print"></i> Imprimir Acta Oficial
            </a>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="admin-card search-card">
        <form action="{{ route('admin.firmantes.index') }}" method="GET" class="filter-form">
            <div class="filter-input">
                <i class="fas fa-search"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar por Nombre, Cédula o Ciudad...">
            </div>
            
            <div class="filter-select">
                <select name="estado">
                    <option value="">Todos los Estados</option>
                    <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="verificado" {{ request('estado') == 'verificado' ? 'selected' : '' }}>Verificado</option>
                </select>
            </div>

            <button type="submit" class="btn btn-gold btn-sm">Filtrar</button>
            @if(request('q') || request('estado'))
                <a href="{{ route('admin.firmantes.index') }}" class="btn btn-outline btn-sm">Limpiar</a>
            @endif
        </form>
    </div>

    <!-- Signers Table -->
    <div class="admin-card">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Nº Acta</th>
                        <th>Nombre y Apellido</th>
                        <th>Cédula / Documento</th>
                        <th>Contacto</th>
                        <th>Ciudad</th>
                        <th>Instrumento</th>
                        <th>Firma Táctil</th>
                        <th>Fecha de Registro</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($firmantes as $firmante)
                        <tr>
                            <td><span class="code-badge">#{{ str_pad($firmante->id, 4, '0', STR_PAD_LEFT) }}</span></td>
                            <td>
                                <strong>{{ $firmante->nombre }} {{ $firmante->apellido }}</strong>
                            </td>
                            <td>{{ $firmante->cedula }}</td>
                            <td>
                                <div class="contact-info">
                                    <span><i class="far fa-envelope"></i> {{ $firmante->email ?? '—' }}</span>
                                    <span><i class="fas fa-phone"></i> {{ $firmante->telefono ?? '—' }}</span>
                                </div>
                            </td>
                            <td>{{ $firmante->ciudad ?? 'Asunción' }}</td>
                            <td>
                                <span class="tag-inst">{{ $firmante->instrumento_principal }}</span>
                            </td>
                            <td>
                                @if($firmante->firma_digital_path)
                                    <a href="{{ route('admin.firmantes.show', $firmante->id) }}" class="signature-preview" title="Ver Firma Ampliada">
                                        <img src="{{ $firmante->firma_digital_path }}" alt="Firma">
                                    </a>
                                @else
                                    <span class="text-muted"><i class="fas fa-minus"></i></span>
                                @endif
                            </td>
                            <td>{{ $firmante->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($firmante->estado_adhesion == 'verificado')
                                    <span class="badge badge-success"><i class="fas fa-check"></i> Verificado</span>
                                @else
                                    <span class="badge badge-warning"><i class="fas fa-clock"></i> Pendiente</span>
                                @endif
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('admin.firmantes.show', $firmante->id) }}" class="btn-action-icon btn-view" title="Detalle Completo">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.firmantes.update_status', $firmante->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="estado" value="{{ $firmante->estado_adhesion == 'verificado' ? 'pendiente' : 'verificado' }}">
                                        <button type="submit" class="btn-action-icon {{ $firmante->estado_adhesion == 'verificado' ? 'btn-undo' : 'btn-check' }}" title="{{ $firmante->estado_adhesion == 'verificado' ? 'Marcar Pendiente' : 'Validar Firma' }}">
                                            <i class="fas {{ $firmante->estado_adhesion == 'verificado' ? 'fa-undo' : 'fa-check' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.firmantes.reenviar_correo', $firmante->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Reenviar correo del Acta Fundacional y credenciales a {{ addslashes($firmante->user->email ?? $firmante->email ?? $firmante->nombre_completo) }}?');">
                                        @csrf
                                        <button type="submit" class="btn-action-icon btn-email" title="Reenviar Acta y Credenciales">
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.firmantes.destroy', $firmante->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar permanentemente la firma de {{ addslashes($firmante->nombre_completo) }} (CI: {{ $firmante->cedula }})?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action-icon btn-delete" title="Eliminar Firma">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-4 text-muted">No se encontraron firmantes con los criterios seleccionados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrapper mt-3">
            {{ $firmantes->links() }}
        </div>
    </div>
</div>

<style>
.page-top-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 1rem;
}
.section-desc {
    color: var(--admin-muted);
    font-size: 0.9rem;
    margin: 0;
}
.action-buttons {
    display: flex;
    gap: 0.75rem;
}
.search-card {
    padding: 1rem 1.25rem;
    margin-bottom: 1.5rem;
}
.filter-form {
    display: flex;
    gap: 0.75rem;
    align-items: center;
    flex-wrap: wrap;
}
.filter-input {
    flex: 1;
    min-width: 250px;
    position: relative;
    display: flex;
    align-items: center;
}
.filter-input i {
    position: absolute;
    left: 0.85rem;
    color: var(--admin-muted);
}
.filter-input input {
    width: 100%;
    background: #090c12;
    border: 1px solid var(--admin-border);
    border-radius: 6px;
    padding: 0.5rem 0.85rem 0.5rem 2.2rem;
    color: #fff;
    font-size: 0.88rem;
    outline: none;
}
.filter-select select {
    background: #090c12;
    border: 1px solid var(--admin-border);
    border-radius: 6px;
    padding: 0.5rem 0.85rem;
    color: #fff;
    font-size: 0.88rem;
    outline: none;
}
.code-badge {
    font-family: monospace;
    color: var(--admin-gold);
    font-size: 0.82rem;
}
.contact-info {
    display: flex;
    flex-direction: column;
    gap: 0.2rem;
    font-size: 0.75rem;
    color: var(--admin-muted);
}
.tag-inst {
    background: rgba(229, 169, 60, 0.1);
    border: 1px solid rgba(229, 169, 60, 0.2);
    color: var(--admin-gold);
    padding: 0.15rem 0.45rem;
    border-radius: 4px;
    font-size: 0.72rem;
}
.signature-preview {
    display: inline-block;
    background: #fff;
    border-radius: 4px;
    padding: 2px 4px;
    width: 60px;
    height: 28px;
}
.signature-preview img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}
.table-actions {
    display: flex;
    gap: 0.4rem;
    align-items: center;
}
.btn-action-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 0.82rem;
    cursor: pointer;
    transition: all 0.2s;
    background: transparent;
}
.btn-view {
    background: rgba(229, 169, 60, 0.1);
    border: 1px solid rgba(229, 169, 60, 0.3);
    color: var(--admin-gold);
}
.btn-view:hover {
    background: var(--admin-gold);
    color: #07090c;
    box-shadow: 0 0 10px rgba(229, 169, 60, 0.4);
}
.btn-check {
    background: rgba(16, 185, 129, 0.1);
    border: 1px solid rgba(16, 185, 129, 0.3);
    color: #34d399;
}
.btn-check:hover {
    background: #10b981;
    color: #fff;
    box-shadow: 0 0 10px rgba(16, 185, 129, 0.4);
}
.btn-undo {
    background: rgba(245, 158, 11, 0.1);
    border: 1px solid rgba(245, 158, 11, 0.3);
    color: #fbbf24;
}
.btn-undo:hover {
    background: #f59e0b;
    color: #000;
}
.btn-email {
    background: rgba(56, 189, 248, 0.1);
    border: 1px solid rgba(56, 189, 248, 0.3);
    color: #38bdf8;
}
.btn-email:hover {
    background: #0284c7;
    border-color: #0284c7;
    color: #fff;
    box-shadow: 0 0 10px rgba(56, 189, 248, 0.4);
}
.btn-delete {
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #f87171;
}
.btn-delete:hover {
    background: #ef4444;
    border-color: #ef4444;
    color: #fff;
    box-shadow: 0 0 10px rgba(239, 68, 68, 0.4);
}
.mt-3 { margin-top: 1.25rem; }
</style>
@endsection
