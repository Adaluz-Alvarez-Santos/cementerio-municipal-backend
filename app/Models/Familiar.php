<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Familiar extends Model
{
    use HasFactory;
    protected $table = 'familiares';
    
    protected $fillable = [
        'CI',
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'parentesco',
        'celular',
        'persona_id',
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }

    public function exhumaciones()
    {
        return $this->hasMany(Exhumacion::class);
    }

}
