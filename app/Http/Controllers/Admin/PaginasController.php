<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pagina;
use Illuminate\Http\Request;

class PaginasController extends Controller
{
    public function index()
    {
        $paginas = Pagina::orderBy('orden')->get();
        return view('admin.paginas.index', compact('paginas'));
    }

    public function edit($id)
    {
        $pagina = Pagina::findOrFail($id);
        return view('admin.paginas.edit', compact('pagina'));
    }

    public function update(Request $request, $id)
    {
        $pagina = Pagina::findOrFail($id);
        $validated = $request->validate([
            'titulo' => 'required|string|max:150',
            'subtitulo' => 'nullable|string|max:200',
            'contenido' => 'required|string',
            'meta_descripcion' => 'nullable|string|max:255',
            'orden' => 'required|integer',
            'activa' => 'required|boolean',
        ]);

        $pagina->update($validated);

        return redirect()->route('admin.paginas.index')->with('success', "Página '{$pagina->titulo}' actualizada.");
    }
}
