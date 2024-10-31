<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inhumacion;
use App\Models\Exhumacion;
use App\Models\Familiar;
use Carbon\Carbon;

class ExhumacionController extends Controller
{
    public function index()
    {
        $exhumaciones = Exhumacion::with('inhumacion.persona')->paginate(10);
        return response()->json($exhumaciones);
    }

    public function store(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nro_comprobante' => 'required|string|max:255',
            'fecha_comprobante' => 'required|date',
            'fecha_exhumacion' => 'required|date',
            'motivo' => 'required|string|max:255',
        ]);

        $inhumacion = Inhumacion::findOrFail($id);

        if (!$this->puedeSerExhumada($inhumacion)) {
            return response()->json(['message' => 'La inhumación no puede ser exhumada aún.'], 400);
        }

        $exhumacion = Exhumacion::create([
            'nro_comprobante' => $validatedData['nro_comprobante'],
            'fecha_comprobante' => $validatedData['fecha_comprobante'],
            'fecha_exhumacion' => $validatedData['fecha_exhumacion'],
            'motivo' => $validatedData['motivo'],
            'inhumacion_id' => $inhumacion->id,
        ]);

        $inhumacion->update(['estado' => 'exhumacion']);

        return response()->json(['message' => 'Exhumación creada con éxito', 'data' => $exhumacion], 201);
    }

    public function exhumar(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nro_comprobante' => 'required|string|max:255',
            'fecha_comprobante' => 'required|date',
            'fecha_exhumacion' => 'required|date',
            'motivo' => 'required|string|max:255',
            'familiar.CI' => 'nullable|string|max:20',
            'familiar.nombre' => 'nullable|string|max:255',
            'familiar.apellido_paterno' => 'nullable|string|max:255',
            'familiar.apellido_materno' => 'nullable|string|max:255',
            'familiar.parentesco' => 'nullable|string|max:255',
            'familiar.celular' => 'nullable|string|max:20',
        ]);

        try {
            $inhumacion = Inhumacion::findOrFail($id);

            if (!$this->puedeSerExhumada($inhumacion)) {
                return response()->json(['message' => 'La inhumación no puede ser exhumada aún.'], 400);
            }

            $familiar = $this->obtenerOFamiliarExistente($inhumacion, $request->familiar);

            $exhumacion = Exhumacion::create([
                'nro_comprobante' => $validatedData['nro_comprobante'],
                'fecha_comprobante' => $validatedData['fecha_comprobante'],
                'fecha_exhumacion' => $validatedData['fecha_exhumacion'],
                'motivo' => $validatedData['motivo'],
                'inhumacion_id' => $inhumacion->id,
                'familiar_id' => $familiar ? $familiar->id : null,
            ]);

            $inhumacion->update(['estado' => 'exhumacion']);

            return response()->json(['message' => 'Exhumación registrada con éxito', 'exhumacion' => $exhumacion], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al registrar la exhumación', 'error' => $e->getMessage()], 500);
        }
    }

    private function puedeSerExhumada($inhumacion)
    {
        $fechaLimite = Carbon::parse($inhumacion->fecha_entrada)->addYears(5);
        if (Carbon::now()->lessThan($fechaLimite)) {
            return false;
        }

        if ($inhumacion->fecha_extendido_hasta && Carbon::now()->lessThan($inhumacion->fecha_extendido_hasta)) {
            return false;
        }

        return true;
    }

    private function obtenerOFamiliarExistente($inhumacion, $familiarData)
    {
        if (!$familiarData || !isset($familiarData['CI'])) {
            return null;
        }

        $familiar = Familiar::where('CI', $familiarData['CI'])->first();

        if (!$familiar) {
            $familiar = Familiar::create([
                'CI' => $familiarData['CI'],
                'nombre' => $familiarData['nombre'],
                'apellido_paterno' => $familiarData['apellido_paterno'],
                'apellido_materno' => $familiarData['apellido_materno'],
                'parentesco' => $familiarData['parentesco'],
                'celular' => $familiarData['celular'],
                'persona_id' => $inhumacion->persona->id,
            ]);
        }

        return $familiar;
    }
}
