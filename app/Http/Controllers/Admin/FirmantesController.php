<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FirmanteActa;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FirmantesController extends Controller
{
    public function index(Request $request)
    {
        $query = FirmanteActa::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('nombre', 'ilike', "%{$s}%")
                  ->orWhere('apellido', 'ilike', "%{$s}%")
                  ->orWhere('cedula', 'ilike', "%{$s}%")
                  ->orWhere('ciudad', 'ilike', "%{$s}%")
                  ->orWhere('instrumento', 'ilike', "%{$s}%")
                  ->orWhere('codigo_verificacion', 'ilike', "%{$s}%");
            });
        }

        if ($request->filled('instrumento')) {
            $query->where('instrumento', $request->instrumento);
        }

        if ($request->filled('ciudad')) {
            $query->where('ciudad', $request->ciudad);
        }

        $instrumentos = FirmanteActa::distinct()->pluck('instrumento')->filter();
        $ciudades = FirmanteActa::distinct()->pluck('ciudad')->filter();
        $firmantes = $query->latest()->paginate(15)->withQueryString();

        return view('admin.firmantes.index', compact('firmantes', 'instrumentos', 'ciudades'));
    }

    public function show($id)
    {
        $firmante = FirmanteActa::with('user')->findOrFail($id);
        return view('admin.firmantes.show', compact('firmante'));
    }

    public function updateEstado(Request $request, $id)
    {
        $firmante = FirmanteActa::findOrFail($id);
        $validated = $request->validate([
            'estado' => 'required|in:pendiente_asamblea,ratificado,rechazado,pendiente,verificado',
        ]);

        $estado = $validated['estado'];
        if ($estado === 'verificado') $estado = 'ratificado';
        if ($estado === 'pendiente') $estado = 'pendiente_asamblea';

        $firmante->update(['estado' => $estado]);

        return back()->with('success', "Estado del firmante actualizado.");
    }

    public function destroy($id)
    {
        $firmante = FirmanteActa::findOrFail($id);
        $nombreCompleto = $firmante->nombre_completo;

        // Eliminar imagen del acta si existe en disco
        $certificatePath = storage_path("app/public/actas/acta_firmada_{$firmante->codigo_verificacion}.png");
        if (file_exists($certificatePath)) {
            @unlink($certificatePath);
        }

        // Eliminar el registro del firmante
        $firmante->delete();

        return redirect()->route('admin.firmantes.index')
            ->with('success', "La firma y registro de {$nombreCompleto} ha sido eliminada correctamente.");
    }

    public function exportarCsv(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="padron_firmantes_curupayty_' . date('Y-m-d') . '.csv"',
        ];

        return response()->stream(function() {
            $handle = fopen('php://output', 'w');
            // UTF-8 BOM para abrir correctamente en Excel
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($handle, [
                'ID',
                'Código Verificación',
                'Nombre',
                'Apellido',
                'Cédula',
                'Email',
                'Ciudad',
                'Dirección',
                'Instrumento / Rol',
                'Sueño Musical',
                'Estado',
                'Fecha de Firma'
            ]);

            FirmanteActa::with('user')->chunk(100, function($firmantes) use ($handle) {
                foreach ($firmantes as $f) {
                    fputcsv($handle, [
                        $f->id,
                        $f->codigo_verificacion,
                        $f->nombre,
                        $f->apellido,
                        $f->cedula,
                        $f->user ? $f->user->email : 'N/A',
                        $f->ciudad,
                        $f->direccion,
                        $f->instrumento,
                        $f->sueno_musical,
                        $f->estado,
                        $f->created_at->format('d/m/Y H:i:s'),
                    ]);
                }
            });

            fclose($handle);
        }, 200, $headers);
    }

    public function actaOficial()
    {
        $firmantes = FirmanteActa::orderBy('id')->get();
        return view('admin.firmantes.acta_oficial', compact('firmantes'));
    }
}
