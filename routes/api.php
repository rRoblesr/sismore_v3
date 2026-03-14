<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UbigeoApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Ejemplo de uso: http://localhost/aaapanel007/sismore_v5/api/ubigeo/descargar/completo
Route::get('/ubigeo/descargar/completo', [UbigeoApiController::class, 'descargarCompleto']);

// Ejemplo de uso: http://localhost/aaapanel007/sismore_v5/api/ubigeo/listar/25
Route::get('/ubigeo/listar/{codigo?}', [UbigeoApiController::class, 'listarHijos']);

// Ejemplo de uso: http://localhost/aaapanel007/sismore_v5/api/ubigeo/250101
Route::get('/ubigeo/{codigo}', [UbigeoApiController::class, 'buscarPorCodigo']);
