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

    </div>

    <!-- Gestión de Usuarios y Asignación de Roles -->
    <div class="admin-card mt-4">
        <div class="admin-card-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
            <div>
                <h3 class="card-title" style="margin:0;"><i class="fas fa-user-shield"></i> Usuarios del Sistema y Asignación de Rangos</h3>
                <p style="font-size:0.85rem; color:var(--admin-muted); margin:0.3rem 0 0;">Asigná o cambiá el rango/rol de cualquier usuario existente o creá uno nuevo directamente.</p>
            </div>
            <button class="btn btn-gold btn-sm" onclick="document.getElementById('newUserCard').scrollIntoView({behavior:'smooth'})">
                <i class="fas fa-user-plus"></i> + Crear Nuevo Usuario
            </button>
        </div>

        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; font-size:0.88rem;">
                <thead>
                    <tr style="border-bottom:1px solid rgba(255,255,255,0.1); text-align:left;">
                        <th style="padding:0.75rem 1rem; color:var(--admin-gold);">Usuario</th>
                        <th style="padding:0.75rem 1rem; color:var(--admin-gold);">Correo Electrónico</th>
                        <th style="padding:0.75rem 1rem; color:var(--admin-gold);">Roles Actuales</th>
                        <th style="padding:0.75rem 1rem; color:var(--admin-gold); text-align:right;">Modificar Rango</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                        <tr style="border-bottom:1px solid rgba(255,255,255,0.05);">
                            <td style="padding:0.85rem 1rem; font-weight:600; color:#fff;">
                                {{ $u->name }}
                                @if($u->email === 'jucfra23@gmail.com')
                                    <span style="font-size:0.7rem; background:rgba(229,169,60,0.2); color:var(--admin-gold); padding:2px 6px; border-radius:4px; margin-left:6px;">SUPERADMIN</span>
                                @endif
                            </td>
                            <td style="padding:0.85rem 1rem; color:#94a3b8; font-family:monospace;">{{ $u->email }}</td>
                            <td style="padding:0.85rem 1rem;">
                                @forelse($u->roles as $r)
                                    <span style="display:inline-block; font-size:0.72rem; font-weight:600; padding:2px 8px; border-radius:12px; background:rgba(255,255,255,0.08); color:#f1f5f9; margin-right:4px;">
                                        {{ $r->display_name }}
                                    </span>
                                @empty
                                    <span style="color:#64748b; font-size:0.8rem;">Sin rol asignado</span>
                                @endforelse
                            </td>
                            <td style="padding:0.85rem 1rem; text-align:right;">
                                <form action="{{ route('admin.roles.assign') }}" method="POST" style="display:inline-flex; align-items:center; gap:0.5rem;">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $u->id }}">
                                    <select name="roles[]" multiple size="1" style="background:#090c12; border:1px solid rgba(255,255,255,0.15); color:#fff; border-radius:4px; padding:4px 8px; font-size:0.8rem;" title="Mantené presionada la tecla Ctrl/Cmd para seleccionar múltiples roles">
                                        @foreach($roles as $r)
                                            <option value="{{ $r->id }}" {{ $u->roles->contains($r->id) ? 'selected' : '' }}>
                                                {{ $r->display_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-gold btn-sm" style="padding:4px 10px; font-size:0.75rem;">
                                        <i class="fas fa-check"></i> Asignar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top:1rem;">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Formulario Crear Nuevo Usuario con Rango -->
    <div class="admin-card mt-4" id="newUserCard">
        <div class="admin-card-header" style="margin-bottom:1.25rem;">
            <h3 class="card-title" style="margin:0;"><i class="fas fa-user-plus"></i> Registrar Nuevo Usuario Administrador / Miembro</h3>
            <p style="font-size:0.85rem; color:var(--admin-muted); margin:0.3rem 0 0;">Creá un nuevo usuario con acceso directo y asignale su rango correspondiente.</p>
        </div>

        <form action="{{ route('admin.roles.create_user') }}" method="POST">
            @csrf
            <div class="form-row-grid">
                <div class="form-group">
                    <label>Nombre y Apellido</label>
                    <input type="text" name="name" class="form-control" required placeholder="Ej: Maestro Compositor">
                </div>
                <div class="form-group">
                    <label>Correo Electrónico (Login)</label>
                    <input type="email" name="email" class="form-control" required placeholder="usuario@curupayty.com">
                </div>
            </div>
            <div class="form-row-grid">
                <div class="form-group">
                    <label>Contraseña de Acceso</label>
                    <input type="password" name="password" class="form-control" required placeholder="Mínimo 6 caracteres">
                </div>
                <div class="form-group">
                    <label>Rango / Roles a otorgar</label>
                    <div style="display:flex; flex-wrap:wrap; gap:0.75rem; margin-top:0.3rem;">
                        @foreach($roles as $r)
                            <label style="display:flex; align-items:center; gap:0.4rem; font-size:0.85rem; color:#f1f5f9; cursor:pointer;">
                                <input type="checkbox" name="roles[]" value="{{ $r->id }}">
                                {{ $r->display_name }}
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-gold mt-2">
                <i class="fas fa-save"></i> Crear Usuario con Roles
            </button>
        </form>
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
                    <input type="text" name="display_name" class="form-control" required placeholder="Nombre descriptivo">
                </div>
                <div class="form-group">
                    <label>Identificador Único (Slug, ej: auditor)</label>
                    <input type="text" name="name" class="form-control" required placeholder="auditor">
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
