<?php

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

//CRUD room_type
Route::get('/types',[TypeController::class,'types']);
Route::get('/type/{id}',[TypeController::class,'type']);
Route::post('/createtype',[TypeController::class,'store']);
Route::delete('/deletetype/{id}',[TypeController::class,'destroy']);
Route::put('/updatetype/{id}', [TypeController::class,'update']);
