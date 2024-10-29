<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Columna extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero',
        'fila_id',
    ];

    public function espacios()
    {
        return $this->hasMany(Espacio::class);
    }

    public function fila()
    {
        return $this->belongsToMany(Fila::class);
    }

}
