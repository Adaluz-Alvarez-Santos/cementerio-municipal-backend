<?php
namespace App\Services;

use App\Models\Persona;
use App\Models\Familiar;
use App\Models\Inhumacion;
use Illuminate\Support\Facades\DB;

class InhumacionService
{
    public function registrarInhumacion($validatedData)
    {
        DB::beginTransaction();

        try {
            // Crear la persona
            $persona = Persona::create([
                'CI' => $validatedData['persona']['CI'],
                'nombre' => $validatedData['persona']['nombre'],
                'apellido_paterno' => $validatedData['persona']['apellido_paterno'],
                'apellido_materno' => $validatedData['persona']['apellido_materno'],
                'fecha_nacimiento' => $validatedData['persona']['fecha_nacimiento'],
                'fecha_fallecimiento' => $validatedData['persona']['fecha_fallecimiento'] ?? null,
                'es_adulto' => $validatedData['persona']['es_adulto'],
            ]);

            // Crear los familiares asociados
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

            // Definir la fecha finalizada (5 años después)
            $fechaFinalizado = \Carbon\Carbon::parse($validatedData['fecha_entrada'])->addYears(5);

            // Crear la inhumación
            $inhumacion = Inhumacion::create([
                'fecha_entrada' => $validatedData['fecha_entrada'],
                'fecha_comprobante' => $validatedData['fecha_comprobante'],
                'nro_comprobante' => $validatedData['nro_comprobante'],
                'fecha_finalizado' => $fechaFinalizado,
                'estado' => 'inhumacion',
                'persona_id' => $persona->id,
            ]);

            DB::commit();

            return $inhumacion;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
