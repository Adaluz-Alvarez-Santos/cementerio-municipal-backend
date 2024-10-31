<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Espacio extends Model
{
    use HasFactory;
    protected $table = 'espacios';
    protected $fillable = ['fila_id', 'columna_id', 'estado'];

    public function fila()
    {
        return $this->belongsTo(Fila::class);
    }

    public function columna()
    {
        return $this->belongsTo(Columna::class);
    }

    public function inhumaciones()
    {
        return $this->hasMany(Inhumacion::class, 'espacio_id');
    }
}
