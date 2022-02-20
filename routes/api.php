<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EquipmentsController;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::prefix('/equipment')->group(function () {
    Route::get('/', [EquipmentsController::class, 'getEquipments']);
    Route::get('/{id}', [EquipmentsController::class, 'getEquipments']);
    Route::post('/', [EquipmentsController::class, 'createEquipment']);
    Route::put('/{id}', [EquipmentsController::class, 'updateEquipment'])->where(['id' => '0-9+']);
    Route::delete('/{id}', [EquipmentsController::class, 'deleteEquipment'])->where(['id' => '0-9+']);
});

