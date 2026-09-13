<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Obra extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'titulo',
        'subtitulo',
        'genero',
        'duracion',
        'ano_creacion',
        'plantilla_instrumental',
        'registro_dinapi',
        'registro_apa',
        'registro_aie',
        'estado',
        'descripcion',
        'audio_path',
        'partitura_pdf_path',
        'particelas_path',
        'midi_path',
        'precio_licencia',
        'reproducciones',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getSlugAttribute(): string
    {
        return Str::slug($this->titulo);
    }

    public function getCompositorNombreAttribute(): string
    {
        return $this->user ? $this->user->name : 'Ensamble Curupayty';
    }

    public function getInstrumentacionAttribute(): string
    {
        return $this->plantilla_instrumental ?? 'Ensamble de Cuerdas';
    }

    public function getDuracionEstimadaAttribute(): ?string
    {
        return $this->duracion;
    }

    public function getAnoComposicionAttribute(): ?int
    {
        return $this->ano_creacion;
    }

    public function getEstadoDinapiAttribute(): string
    {
        return $this->registro_dinapi ? 'registrado' : 'tramite';
    }

    public function getNumeroRegistroDinapiAttribute(): ?string
    {
        return $this->registro_dinapi;
    }

    public function getLicenciaTipoAttribute(): string
    {
        return 'Modelo Curupayty 40/25/10/25';
    }

    public function getPortadaPathAttribute(): ?string
    {
        return null;
    }

    public function getParticellasZipPathAttribute(): ?string
    {
        return $this->particelas_path;
    }

    public function getActivoAttribute(): bool
    {
        return $this->estado === 'en_catalogo';
    }

    public function getTonalidadAttribute(): string
    {
        return 'Re menor / Re Mayor';
    }

    public function getTempoBpmAttribute(): int
    {
        return 120;
    }
}
