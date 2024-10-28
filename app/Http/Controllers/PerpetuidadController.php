<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perpetuidad;
use App\Models\Inhumacion;
use Carbon\Carbon;

class PerpetuidadController extends Controller
{

    public function store(Request $request, $id)
{
   
    $validatedData = $request->validate([
        'nro_comprobante' => 'required|string|max:255',
        'fecha_comprobante' => 'required|date',
        'fecha_perpetuidad' => 'required|date',
        'motivo' => 'required|string|max:255',
    ]);

    $inhumacion = Inhumacion::findOrFail($id);

    $fechaLimite = Carbon::parse($inhumacion->fecha_entrada)->addYears(6);
    if (Carbon::now()->lessThan($fechaLimite)) {
        return response()->json(['message' => 'No se puede establecer perpetuidad hasta que hayan pasado 6 años'], 400);
    }


    $perpetuidad = Perpetuidad::create([
        'nro_comprobante' => $validatedData['nro_comprobante'],
        'fecha_comprobante' => $validatedData['fecha_comprobante'],
        'fecha_perpetuidad' => $validatedData['fecha_perpetuidad'],
        'motivo' => $validatedData['motivo'],
        'inhumacion_id' => $inhumacion->id,
    ]);

    $inhumacion->update(['estado' => 'perpetuidad']);

    return response()->json(['message' => 'Perpetuidad establecida con éxito', 'data' => $perpetuidad], 201);
}
}
