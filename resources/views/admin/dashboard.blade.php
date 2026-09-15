@extends('layouts.admin')

@section('title', 'Tablero Principal')
@section('header-title', 'Tablero de Control Institucional')

@section('content')
<div class="dashboard-overview">
    <!-- Stat Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon icon-gold">
                <i class="fas fa-signature"></i>
            </div>
            <div class="stat-info">
                <span class="stat-label">Total Firmantes Acta</span>
                <span class="stat-value">{{ $totalFirmantes }}</span>
                <span class="stat-trend positive"><i class="fas fa-arrow-up"></i> Convocatoria Abierta</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon icon-emerald">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="stat-info">
                <span class="stat-label">Firmas Validadas</span>
                <span class="stat-value">{{ $firmantesVerificados }}</span>
                <span class="stat-sub">{{ round(($totalFirmantes > 0 ? ($firmantesVerificados / $totalFirmantes)*100 : 0)) }}% verificado</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon icon-blue">
                <i class="fas fa-music"></i>
            </div>
            <div class="stat-info">
                <span class="stat-label">Obras en Catálogo</span>
                <span class="stat-value">{{ $totalObras }}</span>
                <span class="stat-sub">Sinfónico & Cuerdas</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon icon-purple">
                <i class="fas fa-book"></i>
            </div>
            <div class="stat-info">
                <span class="stat-label">Documentos CMS</span>
                <span class="stat-value">{{ $totalPaginas }}</span>
                <span class="stat-sub">Estatutos & Manifiesto</span>
            </div>
        </div>
    </div>

    <!-- Main Grid: Recent Signatures & Quick Tools -->
    <div class="dashboard-grid">
        <!-- Recent Signatures Table -->
        <div class="admin-card col-span-2">
            <div class="admin-card-header">
                <div>
                    <h2 class="card-title"><i class="fas fa-file-signature"></i> Firmas Recientes del Acta Fundacional</h2>
                    <p class="card-subtitle">Últimas adhesiones ciudadanas registradas con firma digital táctil</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('admin.firmantes.index') }}" class="btn btn-outline btn-sm">Ver Todas ({{ $totalFirmantes }})</a>
                    <a href="{{ route('admin.firmantes.acta_oficial') }}" target="_blank" class="btn btn-gold btn-sm">
                        <i class="fas fa-print"></i> Generar Acta Oficial
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Nº Reg.</th>
                            <th>Firmante</th>
                            <th>C.I. / RUC</th>
                            <th>Instrumento</th>
                            <th>Firma Digital</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($firmantesRecientes as $f)
                            <tr>
                                <td><span class="code-tag">#{{ str_pad($f->id, 4, '0', STR_PAD_LEFT) }}</span></td>
                                <td>
                                    <strong>{{ $f->nombre }} {{ $f->apellido }}</strong>
                                    <div class="sub-text">{{ $f->ciudad ?? 'Asunción' }}</div>
                                </td>
                                <td>{{ $f->cedula }}</td>
                                <td>
                                    <span class="instrument-pill">{{ $f->instrumento_principal }}</span>
                                </td>
                                <td>
                                    @if($f->firma_digital_path)
                                        <div class="signature-thumb-box" title="Firma Táctil Registrada">
                                            <img src="{{ $f->firma_digital_path }}" alt="Firma" class="signature-thumb">
                                        </div>
                                    @else
                                        <span class="text-muted"><i class="fas fa-times"></i> Sin firma</span>
                                    @endif
                                </td>
                                <td>{{ $f->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($f->estado_adhesion == 'verificado')
                                        <span class="badge badge-success"><i class="fas fa-check"></i> Verificado</span>
                                    @else
                                        <span class="badge badge-warning"><i class="fas fa-clock"></i> Pendiente</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="display: flex; gap: 0.4rem; align-items: center;">
                                        <a href="{{ route('admin.firmantes.show', $f->id) }}" class="btn-action-view" title="Ver Expediente">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.firmantes.reenviar_correo', $f->id) }}" method="POST" style="display: inline;" data-confirm="¿Deseas reenviar el Acta Fundacional con el certificado oficial y una nueva clave temporal de acceso a <strong>{{ addslashes($f->user->email ?? $f->email ?? $f->nombre_completo) }}</strong>?" data-title="Reenviar Acta y Credenciales" data-btn="Sí, reenviar correo" data-icon="info">
                                            @csrf
                                            <button type="submit" class="btn-action-email" title="Reenviar Acta y Credenciales">
                                                <i class="fas fa-paper-plane"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.firmantes.destroy', $f->id) }}" method="POST" style="display: inline;" data-confirm="¿Estás seguro de que deseas eliminar permanentemente la firma de <strong>{{ addslashes($f->nombre_completo) }}</strong> (CI: {{ $f->cedula }})? Esta acción no se puede deshacer." data-title="¿Eliminar Firma del Padrón?" data-btn="Sí, eliminar definitivamente" data-danger="true" data-icon="warning">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action-delete" title="Eliminar Firma">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">Aún no hay firmantes registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Sidebar / Distribution -->
        <div class="admin-card">
            <div class="admin-card-header">
                <div>
                    <h2 class="card-title"><i class="fas fa-guitar"></i> Instrumentos</h2>
                    <p class="card-subtitle">Diversidad de la orquesta fundacional</p>
                </div>
            </div>

            <div class="instrument-list">
                @forelse($instrumentosStats as $stat)
                    <div class="inst-item">
                        <div class="inst-info">
                            <span class="inst-name">{{ $stat->instrumento_principal }}</span>
                            <span class="inst-count">{{ $stat->total }} músicos</span>
                        </div>
                        <div class="inst-progress-bar">
                            <div class="inst-progress-fill" style="width: {{ ($stat->total / max(1, $totalFirmantes)) * 100 }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">Sin datos aún.</p>
                @endforelse
            </div>

            <hr class="divider">

            <div class="quick-links">
                <h3 class="quick-title">Herramientas Rápidas</h3>
                <a href="{{ route('admin.firmantes.export') }}" class="btn-quick-tool mb-2">
                    <span class="quick-tool-icon" style="background:rgba(16,185,129,0.15); color:#34d399;"><i class="fas fa-file-csv"></i></span>
                    <div class="quick-tool-text">
                        <span class="quick-tool-title">Exportar Padrón</span>
                        <span class="quick-tool-sub">Descargar planilla CSV / Excel</span>
                    </div>
                    <i class="fas fa-arrow-right quick-arrow"></i>
                </a>
                <a href="{{ route('admin.obras.create') }}" class="btn-quick-tool mb-2">
                    <span class="quick-tool-icon" style="background:rgba(59,130,246,0.15); color:#60a5fa;"><i class="fas fa-plus-circle"></i></span>
                    <div class="quick-tool-text">
                        <span class="quick-tool-title">Registrar Nueva Obra</span>
                        <span class="quick-tool-sub">Subir partituras y maquetas</span>
                    </div>
                    <i class="fas fa-arrow-right quick-arrow"></i>
                </a>
                <a href="{{ route('landing') }}" target="_blank" class="btn-quick-tool">
                    <span class="quick-tool-icon" style="background:rgba(229,169,60,0.15); color:var(--admin-gold);"><i class="fas fa-external-link-alt"></i></span>
                    <div class="quick-tool-text">
                        <span class="quick-tool-title">Portal Web Público</span>
                        <span class="quick-tool-sub">Ver página en vivo</span>
                    </div>
                    <i class="fas fa-arrow-right quick-arrow"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}
.stat-card {
    background: var(--admin-card);
    border: 1px solid var(--admin-border);
    border-radius: 12px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1.25rem;
}
.stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}
.icon-gold { background: rgba(229, 169, 60, 0.15); color: var(--admin-gold); border: 1px solid rgba(229, 169, 60, 0.3); }
.icon-emerald { background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); }
.icon-blue { background: rgba(59, 130, 246, 0.15); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.3); }
.icon-purple { background: rgba(168, 85, 247, 0.15); color: #c084fc; border: 1px solid rgba(168, 85, 247, 0.3); }

.stat-info {
    display: flex;
    flex-direction: column;
}
.stat-label {
    font-size: 0.8rem;
    color: var(--admin-muted);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
.stat-value {
    font-size: 1.8rem;
    font-weight: 700;
    color: #fff;
    line-height: 1.2;
    margin: 0.2rem 0;
}
.stat-sub {
    font-size: 0.78rem;
    color: var(--admin-muted);
}
.stat-trend.positive {
    font-size: 0.75rem;
    color: #34d399;
}

.dashboard-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 1.5rem;
}
.col-span-2 {
    grid-column: span 1;
}
.admin-card {
    background: var(--admin-card);
    border: 1px solid var(--admin-border);
    border-radius: 12px;
    padding: 1.75rem;
}
.admin-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 1rem;
}
.card-title {
    font-size: 1.15rem;
    color: #fff;
    margin: 0 0 0.25rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.card-subtitle {
    font-size: 0.82rem;
    color: var(--admin-muted);
    margin: 0;
}
.header-actions {
    display: flex;
    gap: 0.5rem;
}
.table-responsive {
    overflow-x: auto;
}
.admin-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.88rem;
}
.admin-table th {
    text-align: left;
    padding: 0.75rem 0.6rem;
    color: var(--admin-muted);
    border-bottom: 1px solid var(--admin-border);
    font-weight: 600;
    font-size: 0.78rem;
    text-transform: uppercase;
}
.admin-table td {
    padding: 0.85rem 0.6rem;
    border-bottom: 1px solid rgba(255,255,255,0.04);
    vertical-align: middle;
}
.code-tag {
    font-family: monospace;
    color: var(--admin-gold);
    font-size: 0.82rem;
}
.sub-text {
    font-size: 0.75rem;
    color: var(--admin-muted);
}
.instrument-pill {
    background: rgba(229, 169, 60, 0.1);
    color: var(--admin-gold);
    border: 1px solid rgba(229, 169, 60, 0.2);
    padding: 0.2rem 0.55rem;
    border-radius: 4px;
    font-size: 0.75rem;
}
.signature-thumb-box {
    background: #fff;
    border-radius: 4px;
    padding: 2px;
    width: 65px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.signature-thumb {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}
.badge {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    font-size: 0.72rem;
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
    font-weight: 600;
}
.badge-success { background: rgba(16, 185, 129, 0.2); color: #34d399; }
.badge-warning { background: rgba(245, 158, 11, 0.2); color: #fbbf24; }

.btn-action-view {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 6px;
    background: rgba(229, 169, 60, 0.1);
    border: 1px solid rgba(229, 169, 60, 0.3);
    color: var(--admin-gold);
    text-decoration: none;
    font-size: 0.82rem;
    transition: all 0.2s;
}
.btn-action-view:hover {
    background: var(--admin-gold);
    color: #07090c;
    box-shadow: 0 0 10px rgba(229, 169, 60, 0.4);
}

.btn-action-delete {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 6px;
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #f87171;
    cursor: pointer;
    font-size: 0.82rem;
    transition: all 0.2s;
}
.btn-action-delete:hover {
    background: #ef4444;
    border-color: #ef4444;
    color: #fff;
    box-shadow: 0 0 10px rgba(239, 68, 68, 0.4);
}

.btn-action-email {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 6px;
    background: rgba(56, 189, 248, 0.1);
    border: 1px solid rgba(56, 189, 248, 0.3);
    color: #38bdf8;
    cursor: pointer;
    font-size: 0.82rem;
    transition: all 0.2s;
}
.btn-action-email:hover {
    background: #0284c7;
    border-color: #0284c7;
    color: #fff;
    box-shadow: 0 0 10px rgba(56, 189, 248, 0.4);
}

.inst-item {
    margin-bottom: 1rem;
}
.inst-info {
    display: flex;
    justify-content: space-between;
    font-size: 0.85rem;
    margin-bottom: 0.3rem;
}
.inst-name { color: #fff; font-weight: 500; }
.inst-count { color: var(--admin-muted); font-size: 0.78rem; }
.inst-progress-bar {
    width: 100%;
    height: 6px;
    background: rgba(255,255,255,0.06);
    border-radius: 3px;
    overflow: hidden;
}
.inst-progress-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--admin-gold), #f3c267);
    border-radius: 3px;
}
.divider {
    border: none;
    border-top: 1px solid var(--admin-border);
    margin: 1.5rem 0;
}
.quick-title {
    font-size: 0.85rem;
    color: var(--admin-muted);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.75rem;
}
.mb-2 { margin-bottom: 0.6rem; }
.btn-block { display: block; width: 100%; text-align: center; }

.btn-quick-tool {
    display: flex;
    align-items: center;
    gap: 0.85rem;
    padding: 0.75rem 1rem;
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid var(--admin-border);
    border-radius: 8px;
    text-decoration: none;
    color: #fff;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}
.btn-quick-tool:hover {
    background: rgba(229, 169, 60, 0.06);
    border-color: rgba(229, 169, 60, 0.4);
    transform: translateX(4px);
}
.quick-tool-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}
.quick-tool-text {
    display: flex;
    flex-direction: column;
    flex: 1;
}
.quick-tool-title {
    font-size: 0.88rem;
    font-weight: 600;
    color: #f1f5f9;
}
.quick-tool-sub {
    font-size: 0.72rem;
    color: var(--admin-muted);
}
.quick-arrow {
    color: var(--admin-muted);
    font-size: 0.75rem;
    transition: transform 0.2s;
}
.btn-quick-tool:hover .quick-arrow {
    color: var(--admin-gold);
    transform: translateX(3px);
}

@media (max-width: 1024px) {
    .dashboard-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection
