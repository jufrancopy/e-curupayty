<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FirmanteActa extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nombre',
        'apellido',
        'cedula',
        'direccion',
        'ciudad',
        'instrumento',
        'sueno_musical',
        'firma_digital',
        'codigo_verificacion',
        'estado',
        'ip_address',
        'user_agent',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre} {$this->apellido}";
    }

    public function getInstrumentoPrincipalAttribute(): string
    {
        return $this->instrumento ?: 'Músico General';
    }

    public function getFirmaDigitalPathAttribute(): ?string
    {
        return $this->firma_digital;
    }

    public function getEstadoAdhesionAttribute(): string
    {
        return ($this->estado === 'ratificado' || $this->estado === 'verificado') ? 'verificado' : 'pendiente';
    }

    public function getEmailAttribute(): ?string
    {
        return $this->user ? $this->user->email : null;
    }

    public function getTelefonoAttribute(): ?string
    {
        return null;
    }
}
