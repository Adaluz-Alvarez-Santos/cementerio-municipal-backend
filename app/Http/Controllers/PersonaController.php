<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use Illuminate\Http\Request;


class PersonaController extends Controller
{

    public function index()
    {

        $personas = Persona::all();

        $data = [
            'personas' => $personas,
            'status' => 200
        ];

        return response()->json($data, 404);
    }

    public function show($id)
    {
        $persona = Persona::find($id);

        if ($persona) {
            return response()->json([
                'persona' => $persona,
                'status' => 200
            ], 200);
        }

                return response()->json([
            'message' => 'Persona no encontrada',
            'status' => 404
        ], 404);
    }

    public function showFamiliares($id)
    {

        $persona = Persona::with('familiares')->find($id);

        if ($persona) {
            return response()->json([
                'persona' => $persona->only(['id', 'nombre', 'apellido_paterno', 'apellido_materno']),
                'familiares' => $persona->familiares,
                'status' => 200
            ], 200);
        }

        return response()->json([
            'message' => 'Persona no encontrada',
            'status' => 404
        ], 404);
    }
}
