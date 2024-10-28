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

        // Verificar si la inhumación ha pasado los 5 años
        $fechaLimite = Carbon::parse($inhumacion->fecha_entrada)->addYears(5);
        if (Carbon::now()->lessThan($fechaLimite)) {
            return response()->json(['message' => 'No se puede exhumar hasta que hayan pasado 5 años'], 400);
        }

        $fecha = carbon::Parse($inhumacion->fecha_extendido_hasta);

        if (carbon::Parse($inhumacion->fecha_extendido_hasta) <= Carbon::now() || $inhumacion->fecha_extendido_hasta == null) {
            $exhumacion = Exhumacion::create([
                'nro_comprobante' => $validatedData['nro_comprobante'],
                'fecha_comprobante' => $validatedData['fecha_comprobante'],
                'fecha_exhumacion' => $validatedData['fecha_exhumacion'],
                'motivo' => $validatedData['motivo'],
                'inhumacion_id' => $inhumacion->id,
            ]);

       
            $inhumacion->update(['estado' => 'exhumacion']);
        } else {
            return response()->json(['message' => 'La inhumación no puede ser exhumada $fecha'], 400);
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
        $request->validate([
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

            // Verificar si la inhumación ha pasado los 5 años o el periodo de extensión ha terminado
            $fechaLimite = Carbon::parse($inhumacion->fecha_entrada)->addYears(5);
            if (Carbon::now()->lessThan($fechaLimite)) {
                return response()->json(['message' => 'No se puede exhumar hasta que hayan pasado 5 años'], 400);
            }

            if ($inhumacion->fecha_extendido_hasta && Carbon::now()->lessThan($inhumacion->fecha_extendido_hasta)) {
                return response()->json(['message' => 'La inhumación no puede ser exhumada hasta que el periodo de extensión finalice'], 400);
            }


            if ($request->familiar['CI']) {
                // Si el familiar ya existe
                $familiar = Familiar::where('CI', $request->familiar['CI'])->first();
                if (!$familiar) {
                    $familiar = Familiar::create([
                        'CI' => $request->familiar['CI'],
                        'nombre' => $request->familiar['nombre'],
                        'apellido_paterno' => $request->familiar['apellido_paterno'],
                        'apellido_materno' => $request->familiar['apellido_materno'],
                        'parentesco' => $request->familiar['parentesco'],
                        'celular' => $request->familiar['celular'],
                        'persona_id' => $inhumacion->persona->id, 
                    ]);
                }
            }


            $exhumacion = Exhumacion::create([
                'nro_comprobante' => $request->nro_comprobante,
                'fecha_comprobante' => $request->fecha_comprobante,
                'fecha_exhumacion' => $request->fecha_exhumacion,
                'motivo' => $request->motivo,
                'inhumacion_id' => $inhumacion->id, 
                'familiar_id' => $familiar->id, 
            ]);

            $inhumacion->update(['estado' => 'exhumacion']);

            return response()->json(['message' => 'Exhumación registrada con éxito', 'exhumacion' => $exhumacion], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al registrar la exhumación', 'error' => $e->getMessage()], 500);
        }
    }
}
