<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Familiar;
use App\Models\Persona;
use App\Models\Inhumacion;
use App\Models\Espacio;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InhumacionController extends Controller
{

    public function index()
    {

        $inhumaciones = Inhumacion::with('persona')->paginate(10);
        return response()->json($inhumaciones);
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'fecha_entrada' => 'required|date',
            'fecha_comprobante' => 'required|date',
            'nro_comprobante' => 'required|string|max:255',
            'persona.CI' => 'required|string|max:255',
            'persona.nombre' => 'required|string|max:255',
            'persona.apellido_paterno' => 'required|string|max:255',
            'persona.apellido_materno' => 'required|string|max:255',
            'persona.fecha_nacimiento' => 'required|date',
            'persona.fecha_fallecimiento' => 'nullable|date',
            'persona.es_adulto' => 'required|boolean',
            'familiares' => 'required|array|min:1',
            'familiares.*.CI' => 'required|string|max:255',
            'familiares.*.nombre' => 'required|string|max:255',
            'familiares.*.apellido_paterno' => 'required|string|max:255',
            'familiares.*.apellido_materno' => 'required|string|max:255',
            'familiares.*.parentesco' => 'required|string|max:255',
            'familiares.*.celular' => 'required|string|max:255',
            'espacio_id' => 'required|exists:espacios,id',
        ]);

        DB::beginTransaction();

        try {


            $persona = Persona::create([
                'CI' => $validatedData['persona']['CI'],
                'nombre' => $validatedData['persona']['nombre'],
                'apellido_paterno' => $validatedData['persona']['apellido_paterno'],
                'apellido_materno' => $validatedData['persona']['apellido_materno'],
                'fecha_nacimiento' => $validatedData['persona']['fecha_nacimiento'],
                'fecha_fallecimiento' => $validatedData['persona']['fecha_fallecimiento'] ?? null,
                'es_adulto' => $validatedData['persona']['es_adulto'],
            ]);



            foreach ($validatedData['familiares'] as $familiarData) {
                Familiar::create([
                    'CI' => $familiarData['CI'],
                    'nombre' => $familiarData['nombre'],
                    'apellido_paterno' => $familiarData['apellido_paterno'],
                    'apellido_materno' => $familiarData['apellido_materno'],
                    'parentesco' => $familiarData['parentesco'],
                    'celular' => $familiarData['celular'],
                    'persona_id' => $persona->id,
                ]);
            }


            // Usar la fecha de fallecimiento si está disponible, de lo contrario, usar la fecha de entrada
            $fechaFinalizado = $validatedData['persona']['fecha_fallecimiento']
                ? Carbon::parse($validatedData['persona']['fecha_fallecimiento'])->addYears(5)
                : Carbon::parse($validatedData['fecha_entrada'])->addYears(5);


            $inhumacion = Inhumacion::create([
                'fecha_entrada' => $validatedData['fecha_entrada'],
                'fecha_comprobante' => $validatedData['fecha_comprobante'],
                'nro_comprobante' => $validatedData['nro_comprobante'],
                'fecha_finalizado' => $fechaFinalizado,
                'estado' => 'inhumacion',
                'persona_id' => $persona->id,
                'espacio_id' => $validatedData['espacio_id'], // Asignación del espacio
            ]);
            
            // Actualizar el espacio a ocupado
            $espacio = Espacio::findOrFail($validatedData['espacio_id']);
            $espacio->update(['estado' => 'ocupado']);


            DB::commit();

            return response()->json(['message' => 'Inhumación creada con éxito', 'data' => $inhumacion], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error al crear la inhumación', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $inhumacion = Inhumacion::with('persona', 'persona.familiares')->findOrFail($id);

        if ($inhumacion) {
            return response()->json([
                'inhumacion' => $inhumacion,
                'status' => 200
            ], 200);
        }
        return response()->json([
            'message' => 'Persona no encontrada',
            'status' => 404
        ], 404);
    }
}
