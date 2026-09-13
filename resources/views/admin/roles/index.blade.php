@extends('layouts.admin')

@section('title', 'Gestión de Roles y Permisos')
@section('header-title', 'Roles de Usuario y Matriz de Permisos')

@section('content')
<div class="roles-page">
    <div class="page-top-actions">
        <div>
            <p class="section-desc">Definición de privilegios de acceso para la gobernanza institucional del Ensamble Curupayty.</p>
        </div>
        <div>
            <button class="btn btn-gold" onclick="document.getElementById('newRoleCard').scrollIntoView({behavior:'smooth'})">
                <i class="fas fa-plus"></i> Crear Nuevo Rol
            </button>
        </div>
    </div>

    <div class="roles-grid">
        @foreach($roles as $role)
            <div class="admin-card role-card">
                <div class="role-header">
                    <div>
                        <span class="role-slug-badge">{{ $role->slug }}</span>
                        <h3 class="role-name">{{ $role->name }}</h3>
                    </div>
                    <div class="role-users-count">
                        <i class="fas fa-users"></i> {{ $role->users->count() }} usuarios
                    </div>
                </div>

                <p class="role-description">{{ $role->description ?? 'Rol operativo del sistema' }}</p>

                <hr class="divider">

                <h4 class="perms-title"><i class="fas fa-key"></i> Permisos Asignados ({{ $role->permissions->count() }})</h4>
                
                <form action="{{ route('admin.roles.sync_permissions', $role->id) }}" method="POST">
                    @csrf
                    <div class="permissions-checklist">
                        @foreach($permissions as $module => $modulePerms)
                            <div style="font-size:0.72rem; font-weight:700; color:var(--admin-gold); text-transform:uppercase; margin-top:0.4rem;">{{ $module }}</div>
                            @foreach($modulePerms as $perm)
                                <label class="perm-checkbox-label">
                                    <input type="checkbox" name="permissions[]" value="{{ $perm->id }}"
                                        {{ $role->permissions->contains($perm->id) ? 'checked' : '' }}>
                                    <span class="perm-title">{{ $perm->name }}</span>
                                    <span class="perm-slug">{{ $perm->slug }}</span>
                                </label>
                            @endforeach
                        @endforeach
                    </div>
                    
                    <button type="submit" class="btn btn-gold btn-sm btn-block mt-3">
                        <i class="fas fa-sync-alt"></i> Actualizar Permisos
                    </button>
                </form>
            </div>
        @endforeach
    </div>

    <!-- Create Role Section -->
    <div class="admin-card mt-4" id="newRoleCard">
        <div class="admin-card-header">
            <h3 class="card-title"><i class="fas fa-shield-alt"></i> Crear Nuevo Rol de Sistema</h3>
        </div>

        <form action="{{ route('admin.roles.store') }}" method="POST">
            @csrf
            <div class="form-row-grid">
                <div class="form-group">
                    <label>Nombre del Rol (Ej: Auditor Externo)</label>
                    <input type="text" name="name" class="form-control" required placeholder="Nombre descriptivo">
                </div>
                <div class="form-group">
                    <label>Identificador Único (Slug, ej: auditor)</label>
                    <input type="text" name="slug" class="form-control" required placeholder="auditor">
                </div>
            </div>
            <div class="form-group">
                <label>Descripción de Funciones</label>
                <textarea name="description" class="form-control" rows="2" placeholder="Responsabilidades de este rol dentro de la plataforma..."></textarea>
            </div>
            <button type="submit" class="btn btn-gold">
                <i class="fas fa-save"></i> Guardar Rol
            </button>
        </form>
    </div>
</div>

<style>
.roles-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(360px, 1fr));
    gap: 1.5rem;
}
.role-card {
    display: flex;
    flex-direction: column;
}
.role-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 0.75rem;
}
.role-slug-badge {
    font-family: monospace;
    font-size: 0.75rem;
    color: var(--admin-gold);
    background: rgba(229,169,60,0.1);
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
}
.role-name {
    font-size: 1.25rem;
    color: #fff;
    margin: 0.35rem 0 0;
}
.role-users-count {
    font-size: 0.82rem;
    color: var(--admin-muted);
}
.role-description {
    font-size: 0.85rem;
    color: #cbd5e1;
    line-height: 1.5;
}
.perms-title {
    font-size: 0.85rem;
    color: var(--admin-gold);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.75rem;
}
.permissions-checklist {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    max-height: 250px;
    overflow-y: auto;
    padding-right: 0.5rem;
}
.perm-checkbox-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(255,255,255,0.02);
    border: 1px solid rgba(255,255,255,0.05);
    padding: 0.4rem 0.6rem;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.82rem;
}
.perm-checkbox-label:hover {
    background: rgba(255,255,255,0.04);
}
.perm-title {
    flex: 1;
    color: #f1f5f9;
}
.perm-slug {
    font-family: monospace;
    font-size: 0.7rem;
    color: var(--admin-muted);
}
.form-row-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}
.form-group {
    margin-bottom: 1rem;
}
.form-group label {
    display: block;
    font-size: 0.82rem;
    color: var(--admin-muted);
    margin-bottom: 0.35rem;
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
.mt-4 { margin-top: 2rem; }
</style>
@endsection
