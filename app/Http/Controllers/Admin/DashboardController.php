<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FirmanteActa;
use App\Models\Obra;
use App\Models\Pagina;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalFirmantes = FirmanteActa::count();
        $firmantesVerificados = FirmanteActa::whereIn('estado', ['ratificado', 'verificado'])->count();
        $totalObras = Obra::count();
        $totalPaginas = Pagina::count();
        $totalUsuarios = User::count();
        $totalRoles = Role::count();

        // Desglose por instrumento
        $instrumentosStats = FirmanteActa::select('instrumento as instrumento_principal', DB::raw('count(*) as total'))
            ->groupBy('instrumento')
            ->orderByDesc('total')
            ->take(8)
            ->get();

        // Últimos firmantes registrados
        $firmantesRecientes = FirmanteActa::with('user')->latest()->take(6)->get();

        // Últimas obras cargadas
        $ultimasObras = Obra::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalFirmantes',
            'firmantesVerificados',
            'totalObras',
            'totalPaginas',
            'totalUsuarios',
            'totalRoles',
            'instrumentosStats',
            'firmantesRecientes',
            'ultimasObras'
        ));
    }
}
