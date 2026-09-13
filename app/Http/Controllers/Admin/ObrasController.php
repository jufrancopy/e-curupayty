<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Obra;
use App\Models\User;
use Illuminate\Http\Request;

class ObrasController extends Controller
{
    public function index(Request $request)
    {
        $query = Obra::with('user');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('titulo', 'ilike', "%{$s}%")
                  ->orWhere('genero', 'ilike', "%{$s}%")
                  ->orWhere('registro_dinapi', 'ilike', "%{$s}%");
            });
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $obras = $query->latest()->paginate(10)->withQueryString();
        $creadores = User::whereHas('roles', fn($q) => $q->whereIn('name', ['creador', 'admin']))->get();

        return view('admin.obras.index', compact('obras', 'creadores'));
    }

    public function create()
    {
        $creadores = User::whereHas('roles', fn($q) => $q->whereIn('name', ['creador', 'admin']))->get();
        return view('admin.obras.create', compact('creadores'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:150',
            'subtitulo' => 'nullable|string|max:150',
            'user_id' => 'required|exists:users,id',
            'genero' => 'nullable|string|max:100',
            'duracion' => 'nullable|string|max:20',
            'ano_creacion' => 'nullable|integer|min:1800|max:2100',
            'plantilla_instrumental' => 'required|string|max:150',
            'registro_dinapi' => 'nullable|string|max:100',
            'registro_apa' => 'nullable|string|max:100',
            'registro_aie' => 'nullable|string|max:100',
            'estado' => 'required|in:borrador,braintrust,aprobada,en_catalogo',
            'descripcion' => 'nullable|string|max:2000',
            'precio_licencia' => 'nullable|numeric|min:0',
            'audio_file' => 'nullable|file|mimes:mp3,wav,m4a|max:30000',
            'partitura_pdf' => 'nullable|file|mimes:pdf|max:20000',
            'midi_file' => 'nullable|file|mimes:mid,midi|max:5000',
        ]);

        $audioPath = null;
        if ($request->hasFile('audio_file')) {
            $audioPath = $request->file('audio_file')->store('obras/audio', 'public');
        }

        $pdfPath = null;
        if ($request->hasFile('partitura_pdf')) {
            $pdfPath = $request->file('partitura_pdf')->store('obras/partituras', 'public');
        }

        $midiPath = null;
        if ($request->hasFile('midi_file')) {
            $midiPath = $request->file('midi_file')->store('obras/midi', 'public');
        }

        $obra = Obra::create([
            'user_id' => $validated['user_id'],
            'titulo' => $validated['titulo'],
            'subtitulo' => $validated['subtitulo'],
            'genero' => $validated['genero'],
            'duracion' => $validated['duracion'],
            'ano_creacion' => $validated['ano_creacion'] ?: date('Y'),
            'plantilla_instrumental' => $validated['plantilla_instrumental'],
            'registro_dinapi' => $validated['registro_dinapi'],
            'registro_apa' => $validated['registro_apa'],
            'registro_aie' => $validated['registro_aie'],
            'estado' => $validated['estado'],
            'descripcion' => $validated['descripcion'],
            'precio_licencia' => $validated['precio_licencia'] ?: 0,
            'audio_path' => $audioPath,
            'partitura_pdf_path' => $pdfPath,
            'midi_path' => $midiPath,
        ]);

        return redirect()->route('admin.obras.index')->with('success', "Obra '{$obra->titulo}' registrada exitosamente.");
    }

    public function edit($id)
    {
        $obra = Obra::findOrFail($id);
        $creadores = User::whereHas('roles', fn($q) => $q->whereIn('name', ['creador', 'admin']))->get();
        return view('admin.obras.edit', compact('obra', 'creadores'));
    }

    public function update(Request $request, $id)
    {
        $obra = Obra::findOrFail($id);
        $validated = $request->validate([
            'titulo' => 'required|string|max:150',
            'subtitulo' => 'nullable|string|max:150',
            'user_id' => 'required|exists:users,id',
            'genero' => 'nullable|string|max:100',
            'duracion' => 'nullable|string|max:20',
            'ano_creacion' => 'nullable|integer',
            'plantilla_instrumental' => 'required|string|max:150',
            'registro_dinapi' => 'nullable|string|max:100',
            'registro_apa' => 'nullable|string|max:100',
            'registro_aie' => 'nullable|string|max:100',
            'estado' => 'required|in:borrador,braintrust,aprobada,en_catalogo',
            'descripcion' => 'nullable|string',
            'precio_licencia' => 'nullable|numeric|min:0',
        ]);

        if ($request->hasFile('audio_file')) {
            $validated['audio_path'] = $request->file('audio_file')->store('obras/audio', 'public');
        }
        if ($request->hasFile('partitura_pdf')) {
            $validated['partitura_pdf_path'] = $request->file('partitura_pdf')->store('obras/partituras', 'public');
        }
        if ($request->hasFile('midi_file')) {
            $validated['midi_path'] = $request->file('midi_file')->store('obras/midi', 'public');
        }

        $obra->update($validated);

        return redirect()->route('admin.obras.index')->with('success', "Obra '{$obra->titulo}' actualizada.");
    }

    public function destroy($id)
    {
        $obra = Obra::findOrFail($id);
        $obra->delete();
        return back()->with('success', 'Obra eliminada del catálogo.');
    }
}
