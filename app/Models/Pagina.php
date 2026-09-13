<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pagina extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'titulo',
        'subtitulo',
        'contenido',
        'meta_descripcion',
        'orden',
        'activa',
    ];
}
