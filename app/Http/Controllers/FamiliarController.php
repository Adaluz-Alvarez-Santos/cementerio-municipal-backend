<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Familiar;
use App\Models\Persona;

class FamiliarController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'CI' => 'required|string',
            'nombre' => 'required|string',
            'apellido_paterno' => 'required|string',
            'apellido_materno' => 'required|string',
            'parentesco' => 'required|string',
            'celular' => 'required|string',
            'persona_id' => 'required|exists:personas,id',
        ]);

        return Familiar::create($request->all());
    }

    public function show($personaId)
    {
        $familiares = Familiar::where('persona_id', $personaId)->get();
        return response()->json($familiares);
    }


}
