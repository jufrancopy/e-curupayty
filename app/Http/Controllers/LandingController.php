<?php

namespace App\Http\Controllers;

use App\Mail\ActaFundacionalFirmadaMail;
use App\Models\FirmanteActa;
use App\Models\Pagina;
use App\Models\Role;
use App\Models\User;
use App\Services\ActaImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class LandingController extends Controller
{
    public function index()
    {
        $paginas = Pagina::where('activa', true)->orderBy('orden')->get();
        $totalFirmantes = FirmanteActa::count();
        return view('landing.index', compact('paginas', 'totalFirmantes'));
    }

    public function storeFirma(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'cedula' => 'required|string|max:30|unique:firmante_actas,cedula',
            'email' => 'required|email|max:150|unique:users,email',
            'direccion' => 'required|string|max:200',
            'ciudad' => 'required|string|max:100',
            'instrumento' => 'required|string|max:100',
            'sueno_musical' => 'required|string|max:1000',
            'firma_digital' => 'required|string', // Base64 dataURL
        ], [
            'cedula.unique' => 'Este número de cédula ya se encuentra registrado en el Acta Fundacional.',
            'email.unique' => 'Este correo electrónico ya está registrado en el sistema.',
            'firma_digital.required' => 'Por favor, dibuje su firma táctil en el recuadro antes de continuar.',
        ]);

        // 1. Crear usuario en el sistema
        $password = $request->input('password') ?: 'curupayty2026';
        $user = User::create([
            'name' => "{$validated['nombre']} {$validated['apellido']}",
            'email' => $validated['email'],
            'password' => Hash::make($password),
        ]);

        // 2. Asignar rol firmante
        $firmanteRole = Role::where('name', 'firmante')->first();
        if ($firmanteRole) {
            $user->roles()->attach($firmanteRole->id);
        }

        // 3. Crear registro de firma en el acta
        $codigoVerificacion = 'CPY-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4));
        
        $firmante = FirmanteActa::create([
            'user_id' => $user->id,
            'nombre' => $validated['nombre'],
            'apellido' => $validated['apellido'],
            'cedula' => $validated['cedula'],
            'direccion' => $validated['direccion'],
            'ciudad' => $validated['ciudad'],
            'instrumento' => $validated['instrumento'],
            'sueno_musical' => $validated['sueno_musical'],
            'firma_digital' => $validated['firma_digital'],
            'codigo_verificacion' => $codigoVerificacion,
            'estado' => 'pendiente_asamblea',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // 4. Enviar Acta Oficial con Certificado Gráfico por Correo
        try {
            Mail::to($user->email)->send(new ActaFundacionalFirmadaMail($firmante));
        } catch (\Exception $e) {
            Log::error("Error al enviar correo del acta firmada a {$user->email}: " . $e->getMessage());
        }

        // Iniciar sesión
        Auth::login($user);

        return redirect()->route('firma.confirmacion', ['codigo' => $codigoVerificacion])
            ->with('success', '¡Has firmado exitosamente el Acta Fundacional! Te hemos enviado una copia oficial con tu certificado a tu correo.');
    }

    public function confirmacion($codigo)
    {
        $firmante = FirmanteActa::where('codigo_verificacion', $codigo)->firstOrFail();
        $totalFirmantes = FirmanteActa::count();
        return view('landing.confirmacion', compact('firmante', 'totalFirmantes'));
    }

    public function descargarImagen($codigo)
    {
        $firmante = FirmanteActa::where('codigo_verificacion', $codigo)->firstOrFail();
        $service = new ActaImageService();
        $path = $service->generate($firmante);

        return response()->download($path, "Acta_Curupayty_{$firmante->codigo_verificacion}.png", [
            'Content-Type' => 'image/png',
        ]);
    }

    public function reenviarCorreo($codigo)
    {
        $firmante = FirmanteActa::where('codigo_verificacion', $codigo)->firstOrFail();
        
        try {
            Mail::to($firmante->user->email)->send(new ActaFundacionalFirmadaMail($firmante));
            return back()->with('success', "Se ha reenviado el Acta Fundacional con tu certificado gráfico a {$firmante->user->email}.");
        } catch (\Exception $e) {
            Log::error("Error reenviando acta a {$firmante->user->email}: " . $e->getMessage());
            return back()->with('error', "No se pudo reenviar el correo en este momento. Por favor verifica más tarde.");
        }
    }

    public function pagina($slug)
    {
        $pagina = Pagina::where('slug', $slug)->where('activa', true)->firstOrFail();
        return view('landing.pagina', compact('pagina'));
    }
}
