<?php

namespace Tests\Feature;

use App\Models\FirmanteActa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CurupaytyFlowTest extends TestCase
{
    public function test_landing_page_loads(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Ensamble Curupayty');
        $response->assertSee('curupaytu_promocion.mp4');
    }

    public function test_catalogo_loads(): void
    {
        $response = $this->get('/catalogo');
        $response->assertStatus(200);
        $response->assertSee('Catálogo Oficial');
    }

    public function test_obra_detail_loads(): void
    {
        $response = $this->get('/catalogo/curupayty-la-trinchera-y-el-fuego');
        $response->assertStatus(200);
        $response->assertSee('Curupayty: La Trinchera y el Fuego');
        $response->assertSee('Visor de Partitura Digital');
    }

    public function test_documento_manifiesto_loads(): void
    {
        $response = $this->get('/documento/manifiesto');
        $response->assertStatus(200);
        $response->assertSee('Manifiesto');
    }

    public function test_touch_signature_submission(): void
    {
        $sampleSignatureBase64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAKAAAAAgCAYAAABzOcvDAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAA';

        $response = $this->post('/firma-acta', [
            'nombre' => 'María',
            'apellido' => 'Benítez',
            'cedula' => '4987654',
            'email' => 'maria.benitez@test.com',
            'direccion' => 'Av. Mariscal López 1250',
            'ciudad' => 'San Lorenzo',
            'instrumento' => 'Violín Primero',
            'sueno_musical' => 'Dignificar a las orquestas de cámara en el interior del país.',
            'firma_digital' => $sampleSignatureBase64,
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('firmante_actas', [
            'cedula' => '4987654',
            'nombre' => 'María',
            'apellido' => 'Benítez',
            'instrumento' => 'Violín Primero',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'maria.benitez@test.com',
        ]);
    }

    public function test_admin_dashboard_and_acta_oficial(): void
    {
        $admin = User::where('email', 'admin@curupayty.com')->first();
        $this->assertNotNull($admin);

        // Dashboard
        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Tablero de Control Institucional');

        // Firmantes List
        $response = $this->actingAs($admin)->get('/admin/firmantes');
        $response->assertStatus(200);
        $response->assertSee('Padrón de Firmantes');

        // Official Printable Acta
        $response = $this->actingAs($admin)->get('/admin/firmantes/acta-oficial');
        $response->assertStatus(200);
        $response->assertSee('Acta Fundacional y de Constitución');

        // CSV Export
        $response = $this->actingAs($admin)->get('/admin/firmantes/exportar/csv');
        $response->assertStatus(200);
        $response->assertHeader('Content-Disposition', 'attachment; filename="padron_firmantes_curupayty_' . date('Y-m-d') . '.csv"');

        // Roles Management
        $response = $this->actingAs($admin)->get('/admin/roles');
        $response->assertStatus(200);
        $response->assertSee('Roles de Usuario y Matriz de Permisos');

        // CMS Pages Management
        $response = $this->actingAs($admin)->get('/admin/paginas');
        $response->assertStatus(200);
        $response->assertSee('Portal Institucional');
    }
}
