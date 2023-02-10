<?php

use Illuminate\Http\Request;
use function PHPSTORM_META\type;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;

use App\Http\Controllers\TypeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrdersDetailController;

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
Route::get('/type/detail/{type_id}',[TypeController::class,'detailType']);
Route::post('/type',[TypeController::class,'store']);
Route::delete('/type/{id}',[TypeController::class,'destroy']);
Route::put('/type/{id}', [TypeController::class,'update']);

// CRUD room
Route::get('/room',[RoomController::class,'show']); //
Route::get('/room/{id}',[RoomController::class,'detail']); //
Route::post('/room',[RoomController::class,'store']);
Route::delete('/room/{id}',[RoomController::class,'destroy']);
Route::put('/room/{id}', [RoomController::class,'update']);

//Orders
Route::post('/orders', [OrderController::class, 'storeOrder']); //bikin order
Route::get('/orders', [OrderController::class, 'show']);
Route::get('/orders/{id}', [OrderController::class, 'detail']);
Route::put('/orders/status/{id}', [OrderController::class, 'upstatus']); //update status
Route::put('/order/{id}', [OrderController::class,'store']);

//Orders Detail
Route::post('/detailorder',[OrdersDetailController::class,'index']);


//date filter
Route::post('/datefilter',[OrdersDetailController::class,'create']);