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
        return $this->belongsToMany(Fila::class, 'columna_fila', 'columna_id', 'fila_id');
    }

    public function columna()
    {
        return $this->belongsToMany(Columna::class, 'columna_fila', 'fila_id', 'columna_id');
    }
}
