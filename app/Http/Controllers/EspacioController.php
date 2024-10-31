<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bloque;
use App\Models\Fila;
use App\Models\Columna;
use App\Models\Inhumacion;
use App\Models\Espacio;

class EspacioController extends Controller
{
    public function obtenerBloquesConDetalles()
    {
        $bloques = Bloque::with('filas.columnas.espacios.inhumaciones')->get();

        return response()->json(['bloques' => $bloques], 200);
    }


    public function crearBloqueConFilasYColumnas(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'filas' => 'required|integer|min:1',
            'columnas' => 'required|integer|min:1',
        ]);

        try {
            $bloque = Bloque::create([
                'nombre' => $request->nombre,
            ]);

            for ($filaNumero = 1; $filaNumero <= $request->filas; $filaNumero++) {
                $fila = Fila::create([
                    'bloque_id' => $bloque->id,
                    'numero' => $filaNumero,
                ]);

                for ($columnaNumero = 1; $columnaNumero <= $request->columnas; $columnaNumero++) {
                    $columna = Columna::create([
                        'fila_id' => $fila->id,
                        'numero' => $columnaNumero,
                    ]);

                    Espacio::create([
                        'fila_id' => $fila->id,
                        'columna_id' => $columna->id,
                        'estado' => 'disponible',
                    ]);
                }
            }

            return response()->json([
                'message' => 'Bloque creado con filas, columnas y espacios',
                'bloque' => $bloque,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al crear el bloque',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // En tu controlador EspacioController.php o HistorialController.php
    public function obtenerHistorial($espacio_id)
    { 
        // Obtener el historial de inhumaciones para el espacio dado
        $inhumaciones = Inhumacion::with('persona')
            ->where('espacio_id', $espacio_id)
            ->get();
    
        // Retorna el historial de inhumaciones en formato JSON
        return response()->json([
            'espacio' => $espacio_id,
            'inhumaciones' => $inhumaciones,
        ], 200);
    }
}
