<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exhumacion extends Model
{
    use HasFactory;
    protected $table = 'exhumaciones';

    protected $fillable = [
        'nro_comprobante',
        'fecha_comprobante',
        'fecha_exhumacion',
        'motivo',
        'inhumacion_id',
        'familiar_id',
    ];

    public function inhumacion(){
        return $this->belongsTo(Inhumacion::class);
    }

    public function familiar()
    {
        return $this->belongsTo(Familiar::class);
    }
}
