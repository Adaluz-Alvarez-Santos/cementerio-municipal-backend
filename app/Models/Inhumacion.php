<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inhumacion extends Model
{
    use HasFactory;

    protected $table = 'inhumaciones';

    protected $fillable = [
        'fecha_entrada',
        'fecha_comprobante',
        'nro_comprobante',
        'fecha_finalizado',
        'fecha_extendido_hasta',
        'estado',
        'persona_id',
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }

    public function exhumacion()
    {
        return $this->hasOne(Exhumacion::class);
    }
    public function perpetuidad()
{
    return $this->hasOne(Perpetuidad::class);
}
}
