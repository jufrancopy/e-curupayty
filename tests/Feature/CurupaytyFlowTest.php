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
        $testCedula = (string) rand(4000000, 4999999);
        $testEmail = 'maria_' . uniqid() . '@test.com';

        $response = $this->post('/firma-acta', [
            'nombre' => 'María',
            'apellido' => 'Benítez',
            'cedula' => $testCedula,
            'telefono' => '0981123456',
            'email' => $testEmail,
            'direccion' => 'Av. Mariscal López 1250',
            'ciudad' => 'San Lorenzo',
            'instrumento' => 'Violín Primero',
            'sueno_musical' => 'Dignificar a las orquestas de cámara en el interior del país.',
            'firma_digital' => $sampleSignatureBase64,
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('firmante_actas', [
            'cedula' => $testCedula,
            'nombre' => 'María',
            'apellido' => 'Benítez',
            'telefono' => '0981123456',
            'instrumento' => 'Violín Primero',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => $testEmail,
        ]);
    }

    public function test_touch_signature_requires_telefono(): void
    {
        $sampleSignatureBase64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAKAAAAAgCAYAAABzOcvDAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAA';

        $response = $this->post('/firma-acta', [
            'nombre' => 'Pedro',
            'apellido' => 'Gómez',
            'cedula' => '3334445',
            // Sin teléfono
            'email' => 'pedro.gomez@test.com',
            'direccion' => 'Iturbe 890',
            'ciudad' => 'Asunción',
            'instrumento' => 'Flauta Traversa',
            'sueno_musical' => 'Crear una escuela de música.',
            'firma_digital' => $sampleSignatureBase64,
        ]);

        $response->assertSessionHasErrors(['telefono']);
    }

    public function test_touch_signature_sends_email_with_certificate(): void
    {
        \Illuminate\Support\Facades\Mail::fake();

        $sampleSignatureBase64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAKAAAAAgCAYAAABzOcvDAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAA';
        $testEmail = 'firmante_' . uniqid() . '@test.com';
        $testCedula = (string) rand(6000000, 7999999);

        $response = $this->post('/firma-acta', [
            'nombre' => 'Rodrigo',
            'apellido' => 'Vargas',
            'cedula' => $testCedula,
            'telefono' => '0971987654',
            'email' => $testEmail,
            'direccion' => 'Palma 450',
            'ciudad' => 'Asunción',
            'instrumento' => 'Compositor',
            'sueno_musical' => 'Estrenar sinfonías nacionales.',
            'firma_digital' => $sampleSignatureBase64,
        ]);

        $response->assertStatus(302);

        \Illuminate\Support\Facades\Mail::assertSent(\App\Mail\ActaFundacionalFirmadaMail::class, function ($mail) use ($testEmail) {
            return $mail->hasTo($testEmail) && !empty($mail->temporaryPassword);
        });

        $firmante = FirmanteActa::where('cedula', $testCedula)->first();
        $this->assertNotNull($firmante);
        $this->assertEquals('0971987654', $firmante->telefono);

        // Test downloading certificate image
        $downloadResponse = $this->get("/acta/{$firmante->codigo_verificacion}/imagen");
        $downloadResponse->assertStatus(200);
        $downloadResponse->assertHeader('Content-Type', 'image/png');
    }

    public function test_admin_dashboard_and_acta_oficial(): void
    {
        $admin = User::where('email', 'jucfra23@gmail.com')->first();
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

    public function test_admin_can_delete_firmante(): void
    {
        $admin = User::where('email', 'jucfra23@gmail.com')->first();
        $this->assertNotNull($admin);

        $firmante = FirmanteActa::create([
            'nombre' => 'Para',
            'apellido' => 'Borrar',
            'cedula' => '99999999',
            'ciudad' => 'Asunción',
            'direccion' => 'Test',
            'instrumento' => 'Violín',
            'sueno_musical' => 'Test',
            'firma_digital' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAA=',
            'codigo_verificacion' => 'CPY-DEL-TEST',
            'estado' => 'pendiente_asamblea',
        ]);

        $response = $this->actingAs($admin)->delete("/admin/firmantes/{$firmante->id}");
        $response->assertRedirect('/admin/firmantes');
        $this->assertDatabaseMissing('firmante_actas', ['id' => $firmante->id]);
    }
}
