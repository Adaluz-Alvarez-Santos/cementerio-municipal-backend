<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\InhumacionController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\FamiliarController;
use App\Http\Controllers\ExhumacionController;
use App\Http\Controllers\PerpetuidadController;
use App\Http\Controllers\EspacioController;


Route::prefix('inhumaciones')->group(function () {
    Route::post('/', [InhumacionController::class, 'store']); 
    Route::get('/', [InhumacionController::class, 'index']); 
    Route::get('/{id}', [InhumacionController::class, 'show']); 
    Route::put('/{id}', [InhumacionController::class, 'update']); 
    Route::delete('/{id}', [InhumacionController::class, 'destroy']); 
    Route::post('/{inhumacionId}/exhumacion', [ExhumacionController::class, 'store']);
    route::put('/{id}/extender', [InhumacionController::class, 'extender']);
});

Route::prefix('personas')->group(function () {
    Route::post('/', [PersonaController::class, 'store']); 
    Route::get('/', [PersonaController::class, 'index']); 
    Route::get('/{id}', [PersonaController::class, 'show']); 
    route::get('/{id}/familiares', [PersonaController::class, 'showFamiliares']);
});

Route::prefix('familiares')->group(function () {
    Route::post('/', [FamiliarController::class, 'store']);
    Route::get('/', [FamiliarController::class, 'index']); 
    Route::get('/{personaId}', [FamiliarController::class, 'show']);
});

Route::prefix('exhumaciones')->group(function () {
    Route::post('/{id}', [ExhumacionController::class, 'store2']); 
    Route::get('/', [ExhumacionController::class, 'index']);
    Route::post('/{id}', [ExhumacionController::class, 'exhumar']); 
});

Route::prefix('perpetuidades')->group(function () {
    Route::post('/{id}', [PerpetuidadController::class, 'store']); 
    Route::get('/', [PerpetuidadController::class, 'index']); 
    Route::get('/{id}', [PerpetuidadController::class, 'show']);
});



// Ruta para obtner los bloques y espacios
Route::post('/bloques', [EspacioController::class, 'crearBloqueConFilasYColumnas']);
Route::patch('/espacios/{filaId}/{columnaId}/estado', [EspacioController::class, 'cambiarEstadoEspacio']);
Route::get('/bloques', [EspacioController::class, 'obtenerBloquesConDetalles']);
