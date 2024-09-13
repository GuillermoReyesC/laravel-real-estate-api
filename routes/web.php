<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PropiedadController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\SolicitudVisitaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::apiResource('propiedades', PropiedadController::class);
Route::apiResource('personas', PersonaController::class);
Route::apiResource('solicitudes', SolicitudVisitaController::class);
   




// Ruta para personas
Route::get('/personas', function () {
    return view('personas');
})->name('personas');


// Ruta para la vista de viviendas
Route::get('/propiedades', function () {
    return view('propiedades');
})->name('propiedades');

// Ruta para solicitud de visita
Route::get('/solicitud', function () {
    return view('solicitud');
})->name('solicitud');