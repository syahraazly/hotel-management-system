<?php

use App\Http\Controllers\RoomController;
use App\Http\Controllers\TypeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use function PHPSTORM_META\type;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//CRUD type
Route::get('/type',[TypeController::class,'show']);
Route::get('/type/{id}',[TypeController::class,'detail']);
Route::post('/type',[TypeController::class,'store']);
Route::delete('/type/{id}',[TypeController::class,'destroy']);
Route::put('/type/{id}', [TypeController::class,'update']);

// CRUD room
Route::get('/room/{id}',[RoomController::class,'detail']); //
Route::post('/room',[RoomController::class,'store']);
Route::delete('/room/{id}',[RoomController::class,'destroy']);
Route::put('/room/{id}', [RoomController::class,'update']);
