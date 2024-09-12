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
Route::apiResource('solicitudes-visitas', SolicitudVisitaController::class);