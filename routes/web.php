<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FirmantesController;
use App\Http\Controllers\Admin\ObrasController;
use App\Http\Controllers\Admin\PaginasController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\CatalogoPublicoController;
use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;

// 1. Rutas Públicas (Landing, Video, Firma Táctil)
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::post('/firma-acta', [LandingController::class, 'storeFirma'])->name('acta.firmar');
Route::post('/acta/firmar', [LandingController::class, 'storeFirma'])->name('firma.store');
Route::get('/firma-confirmacion/{codigo}', [LandingController::class, 'confirmacion'])->name('acta.confirmacion');
Route::get('/acta/confirmacion/{codigo}', [LandingController::class, 'confirmacion'])->name('firma.confirmacion');
Route::get('/acta/{codigo}/imagen', [LandingController::class, 'descargarImagen'])->name('acta.descargar_imagen');
Route::post('/acta/{codigo}/reenviar-correo', [LandingController::class, 'reenviarCorreo'])->name('acta.reenviar_correo');

// Documentos Institucionales CMS (Manifiesto, Estatuto, Asamblea)
Route::get('/documento/{slug}', [LandingController::class, 'pagina'])->name('pagina.show');
Route::get('/pagina/{slug}', [LandingController::class, 'pagina']);

// 2. Catálogo Público de Obras y Partituras Reproducibles
Route::get('/catalogo', [CatalogoPublicoController::class, 'index'])->name('catalogo.index');
Route::get('/catalogo/{obra}', [CatalogoPublicoController::class, 'show'])->name('catalogo.show');

// 3. Autenticación Administrativa
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// 4. Panel de Administración (Protegido por Autenticación)
Route::prefix('admin')->middleware('auth')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Firmantes del Acta Fundacional
    Route::get('/firmantes', [FirmantesController::class, 'index'])->name('firmantes.index');
    Route::get('/firmantes/exportar/csv', [FirmantesController::class, 'exportarCsv'])->name('firmantes.export');
    Route::get('/firmantes/exportar-csv', [FirmantesController::class, 'exportarCsv'])->name('firmantes.exportar_csv');
    Route::get('/firmantes/acta-oficial', [FirmantesController::class, 'actaOficial'])->name('firmantes.acta_oficial');
    Route::get('/firmantes/{id}', [FirmantesController::class, 'show'])->name('firmantes.show');
    Route::post('/firmantes/{id}/estado', [FirmantesController::class, 'updateEstado'])->name('firmantes.update_status');
    Route::post('/firmantes/{id}/update-estado', [FirmantesController::class, 'updateEstado'])->name('firmantes.update_estado');
    Route::delete('/firmantes/{id}', [FirmantesController::class, 'destroy'])->name('firmantes.destroy');

    // Roles y Permisos
    Route::get('/roles', [RolesController::class, 'index'])->name('roles.index');
    Route::post('/roles', [RolesController::class, 'store'])->name('roles.store');
    Route::put('/roles/{id}', [RolesController::class, 'update'])->name('roles.update');
    Route::post('/roles/{id}/permisos', [RolesController::class, 'update'])->name('roles.sync_permissions');
    Route::post('/roles/assign', [RolesController::class, 'assignUserRole'])->name('roles.assign');
    Route::post('/roles/create-user', [RolesController::class, 'createUser'])->name('roles.create_user');

    // Obras y Catálogo (CRUD)
    Route::resource('obras', ObrasController::class);

    // CMS Páginas Institucionales
    Route::resource('paginas', PaginasController::class)->only(['index', 'edit', 'update']);
});
