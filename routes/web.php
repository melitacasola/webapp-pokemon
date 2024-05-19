<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PokemonController;

Route::get('/buscar', [PokemonController::class, 'mostrarFormulario'])->name('buscar.formulario');
Route::post('/buscar', [PokemonController::class, 'procesarBusqueda'])->name('buscar.procesar');
Route::get('/historial', [PokemonController::class, 'mostrarHistorial'])->name('buscar.historial');


Route::get('/', function () {
    return view('welcome');
});
