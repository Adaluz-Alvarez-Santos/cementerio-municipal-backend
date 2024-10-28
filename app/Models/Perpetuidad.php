<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perpetuidad extends Model
{
    use HasFactory;
    protected $table = 'perpetuidades';

    protected $fillable = [
        'nro_comprobante',
        'fecha_comprobante',
        'fecha_perpetuidad',
        'motivo',
        'inhumacion_id',
    ];

    public function inhumacion()
    {
        return $this->belongsTo(Inhumacion::class);
    }
}
