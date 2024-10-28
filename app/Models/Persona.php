<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;
    
    protected $table = 'personas';

    protected $fillable = [
        'CI',
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'fecha_nacimiento',
        'fecha_fallecimiento',
        'es_adulto',
    ];

    public function familiares()
    {
        return $this->hasMany(Familiar::class, 'persona_id');
    }

    public function inhumacion()
    {
        return $this->hasOne(Inhumacion::class, 'persona_id');
    }

}
