<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fila extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero',
        'bloque_id'
    ];

    public function bloque()
    {
        return $this->belongsTo(Bloque::class);
    }

    public function columnas()
    {
        return $this->hasMany(Columna::class);
    }

    public function espacios()
    {
        return $this->hasMany(Espacio::class);
    }
}
