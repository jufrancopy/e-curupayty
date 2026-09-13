<?php

namespace App\Http\Controllers;

use App\Models\Obra;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CatalogoPublicoController extends Controller
{
    public function index(Request $request)
    {
        $query = Obra::with('user')->where('estado', 'en_catalogo');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('titulo', 'ilike', "%{$q}%")
                    ->orWhere('subtitulo', 'ilike', "%{$q}%")
                    ->orWhere('plantilla_instrumental', 'ilike', "%{$q}%")
                    ->orWhereHas('user', fn($u) => $u->where('name', 'ilike', "%{$q}%"));
            });
        }

        if ($request->filled('genero')) {
            $query->where('genero', $request->genero);
        }

        $obras = $query->latest()->paginate(9)->withQueryString();

        return view('catalogo.index', compact('obras'));
    }

    public function show($identifier)
    {
        if (is_numeric($identifier)) {
            $obra = Obra::with('user')->findOrFail($identifier);
        } else {
            // Find by matching slugified title or approximate title
            $obras = Obra::with('user')->get();
            $obra = $obras->first(function ($item) use ($identifier) {
                return Str::slug($item->titulo) === $identifier || Str::slug($item->subtitulo ?? '') === $identifier;
            });

            if (!$obra) {
                $cleanName = str_replace('-', ' ', $identifier);
                $obra = Obra::with('user')->where('titulo', 'ilike', "%{$cleanName}%")->firstOrFail();
            }
        }

        $obra->increment('reproducciones');
        return view('catalogo.show', compact('obra'));
    }
}
