@extends('layouts.admin')

@section('title', 'Expediente de Firmante #' . str_pad($firmante->id, 4, '0', STR_PAD_LEFT))
@section('header-title', 'Expediente Oficial de Adhesión Fundacional')

@section('content')
<div class="firmante-detail-page">
    <div class="mb-3">
        <a href="{{ route('admin.firmantes.index') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-arrow-left"></i> Volver al Padrón de Firmantes
        </a>
    </div>

    <div class="detail-grid">
        <!-- Main Data Card -->
        <div class="admin-card">
            <div class="admin-card-header">
                <div>
                    <span class="code-badge">REGISTRO ACTA #{{ str_pad($firmante->id, 4, '0', STR_PAD_LEFT) }}</span>
                    <h2 class="card-title mt-1">{{ $firmante->nombre }} {{ $firmante->apellido }}</h2>
                    <p class="card-subtitle">Miembro Adherente — Asamblea Constituyente Curupayty</p>
                </div>
                <div>
                    @if($firmante->estado_adhesion == 'verificado')
                        <span class="badge badge-success"><i class="fas fa-check-circle"></i> Firma Verificada</span>
                    @else
                        <span class="badge badge-warning"><i class="fas fa-clock"></i> Pendiente de Validación</span>
                    @endif
                </div>
            </div>

            <div class="info-table">
                <div class="info-row">
                    <span class="info-label"><i class="fas fa-id-card"></i> Cédula de Identidad:</span>
                    <strong class="info-value">{{ $firmante->cedula }}</strong>
                </div>
                <div class="info-row">
                    <span class="info-label"><i class="far fa-envelope"></i> Correo Electrónico:</span>
                    <span class="info-value">{{ $firmante->email ?? 'No registrado' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label"><i class="fas fa-phone"></i> Teléfono / WhatsApp:</span>
                    <span class="info-value">{{ $firmante->telefono ?? 'No registrado' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label"><i class="fas fa-map-marker-alt"></i> Dirección:</span>
                    <span class="info-value">{{ $firmante->direccion ?? 'No consignada' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label"><i class="fas fa-city"></i> Ciudad:</span>
                    <span class="info-value">{{ $firmante->ciudad ?? 'Asunción' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label"><i class="fas fa-guitar"></i> Instrumento Principal:</span>
                    <span class="info-value highlight-gold">{{ $firmante->instrumento_principal }}</span>
                </div>
                <div class="info-row dream-row">
                    <span class="info-label"><i class="fas fa-star"></i> Sueño Musical / Visión:</span>
                    <div class="dream-box">
                        "{{ $firmante->sueno_musical ?? 'Fortalecer el sinfonismo paraguayo con dignidad y justicia para los intérpretes.' }}"
                    </div>
                </div>
            </div>
        </div>

        <!-- Signature & Audit Card -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h3 class="card-title"><i class="fas fa-signature"></i> Firma Manuscrita Digital (Touch)</h3>
            </div>

            <div class="signature-canvas-display">
                @if($firmante->firma_digital_path)
                    <img src="{{ $firmante->firma_digital_path }}" alt="Firma Digital" class="signature-large-img">
                @else
                    <p class="text-muted text-center py-4">No se ha registrado trazo de firma táctil.</p>
                @endif
            </div>
            <p class="signature-caption">Captura criptográfica en pantalla táctil con timestamp digital.</p>

            <hr class="divider">

            <h4 class="card-subtitle mb-2"><i class="fas fa-shield-alt"></i> Datos Técnicos de Auditoría</h4>
            <ul class="audit-list">
                <li><strong>Dirección IP:</strong> {{ $firmante->ip_address ?? '127.0.0.1' }}</li>
                <li><strong>Fecha y Hora:</strong> {{ $firmante->created_at->format('d/m/Y H:i:s') }}</li>
                <li><strong>Agente de Navegación:</strong> <span class="ua-text">{{ Str::limit($firmante->user_agent ?? 'Mozilla/5.0 WebKit', 60) }}</span></li>
            </ul>

            <hr class="divider">

            <div class="status-action-box">
                <h4>Gestionar Estado Legal</h4>
                <form action="{{ route('admin.firmantes.update_status', $firmante->id) }}" method="POST">
                    @csrf
                    <div class="status-options">
                        <label class="status-radio">
                            <input type="radio" name="estado_adhesion" value="verificado" {{ $firmante->estado_adhesion == 'verificado' ? 'checked' : '' }}>
                            <span><i class="fas fa-check text-emerald"></i> Verificado (Aprobado para Acta)</span>
                        </label>
                        <label class="status-radio">
                            <input type="radio" name="estado_adhesion" value="pendiente" {{ $firmante->estado_adhesion == 'pendiente' ? 'checked' : '' }}>
                            <span><i class="fas fa-clock text-gold"></i> En Revisión / Pendiente</span>
                        </label>
                    </div>
                    <button type="submit" class="btn btn-gold btn-block mt-2">
                        <i class="fas fa-save"></i> Guardar Modificación
                    </button>
                </form>
            </div>

            <div class="status-action-box" style="background: rgba(56, 189, 248, 0.06); border: 1px solid rgba(56, 189, 248, 0.25); border-radius: 8px; padding: 1.2rem; margin-top: 1.5rem;">
                <h4 style="color: #38bdf8; font-size: 0.9rem; margin-bottom: 0.4rem;">
                    <i class="fas fa-paper-plane"></i> Notificación por Correo & Credenciales
                </h4>
                <p style="font-size: 0.8rem; color: #cbd5e1; margin-bottom: 0.85rem; line-height: 1.5;">
                    Reenvía el Acta Fundacional oficial junto al certificado gráfico en alta resolución y una nueva clave única provisional de acceso al sistema (destinatario: <strong>{{ $firmante->user->email ?? $firmante->email ?? 'No registrado' }}</strong>).
                </p>
                <form action="{{ route('admin.firmantes.reenviar_correo', $firmante->id) }}" method="POST" onsubmit="return confirm('¿Reenviar correo oficial a {{ addslashes($firmante->user->email ?? $firmante->email ?? $firmante->nombre_completo) }}?');">
                    @csrf
                    <button type="submit" class="btn btn-block" style="background: #0284c7; color: #fff; border: none; padding: 0.65rem; border-radius: 6px; font-weight: 600; cursor: pointer; width: 100%; transition: background 0.2s; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                        <i class="fas fa-paper-plane"></i> Reenviar Acta y Credenciales
                    </button>
                </form>
            </div>

            <hr class="divider" style="margin-top:1.5rem;">

            <div class="danger-zone" style="background:rgba(239,68,68,0.06); border:1px solid rgba(239,68,68,0.25); border-radius:8px; padding:1.2rem;">
                <h4 style="color:#ef4444; font-size:0.9rem; margin-bottom:0.4rem;"><i class="fas fa-exclamation-triangle"></i> Zona de Peligro</h4>
                <p style="font-size:0.8rem; color:#cbd5e1; margin-bottom:0.85rem;">Elimina esta firma y su certificado del padrón oficial permanentemente.</p>
                <form action="{{ route('admin.firmantes.destroy', $firmante->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar permanentemente la firma de {{ addslashes($firmante->nombre_completo) }} (CI: {{ $firmante->cedula }})? Esta acción no se puede deshacer.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-block" style="background:#dc2626; color:#fff; border:none; padding:0.6rem; border-radius:6px; font-weight:600; cursor:pointer; width:100%; transition:background 0.2s;">
                        <i class="fas fa-trash-alt"></i> Eliminar Firma del Padrón
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.mb-3 { margin-bottom: 1.5rem; }
.detail-grid {
    display: grid;
    grid-template-columns: 1.5fr 1fr;
    gap: 1.5rem;
}
.mt-1 { margin-top: 0.35rem; }
.info-table {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-top: 1rem;
}
.info-row {
    display: flex;
    justify-content: space-between;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid rgba(255,255,255,0.05);
    font-size: 0.92rem;
}
.info-label {
    color: var(--admin-muted);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.info-value {
    color: #fff;
}
.highlight-gold {
    color: var(--admin-gold);
    font-weight: 600;
}
.dream-row {
    flex-direction: column;
    gap: 0.5rem;
    border-bottom: none;
}
.dream-box {
    background: rgba(229, 169, 60, 0.05);
    border-left: 3px solid var(--admin-gold);
    padding: 1rem;
    border-radius: 0 6px 6px 0;
    font-style: italic;
    color: #cbd5e1;
    line-height: 1.6;
}
.signature-canvas-display {
    background: #ffffff;
    border: 1px solid var(--admin-gold);
    border-radius: 8px;
    padding: 1rem;
    text-align: center;
    min-height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.signature-large-img {
    max-width: 100%;
    max-height: 140px;
    object-fit: contain;
}
.signature-caption {
    font-size: 0.75rem;
    color: var(--admin-muted);
    text-align: center;
    margin-top: 0.5rem;
}
.audit-list {
    list-style: none;
    padding: 0;
    margin: 0;
    font-size: 0.8rem;
}
.audit-list li {
    padding: 0.35rem 0;
    color: var(--admin-muted);
}
.audit-list strong {
    color: #fff;
}
.ua-text {
    word-break: break-all;
    font-family: monospace;
    font-size: 0.72rem;
}
.status-action-box {
    background: rgba(255,255,255,0.02);
    border: 1px solid var(--admin-border);
    border-radius: 8px;
    padding: 1rem;
}
.status-action-box h4 {
    font-size: 0.88rem;
    margin: 0 0 0.75rem;
    color: #fff;
}
.status-options {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}
.status-radio {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.82rem;
    cursor: pointer;
}
.text-emerald { color: #34d399; }
.text-gold { color: var(--admin-gold); }
@media (max-width: 900px) {
    .detail-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection
