<?php

use Illuminate\Http\Request;
use function PHPSTORM_META\type;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;

use App\Http\Controllers\TypeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrdersDetailController;
use App\Http\Controllers\UserController;

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
Route::post('/login', [UserController::class, 'login']);

Route::group(['middleware' => ['jwt.verify']], function(){
    Route::group(['middleware' => ['api.receptionist']], function(){

    });
    Route::group(['middleware' => ['api.admin']], function(){
        
    });
    
});

//CRUD type
Route::get('/type',[TypeController::class,'show']);
Route::get('/type/{id}',[TypeController::class,'detail']);
Route::get('/type/detail/{type_id}',[TypeController::class,'detailType']);
Route::post('/type',[TypeController::class,'store']);
Route::delete('/type/{id}',[TypeController::class,'destroy']);
Route::post('/type/{id}', [TypeController::class,'update']);

// CRUD room
Route::get('/room',[RoomController::class,'show']); //
Route::get('/room/{id}',[RoomController::class,'detail']); //
Route::post('/room',[RoomController::class,'store']);
Route::delete('/room/{id}',[RoomController::class,'destroy']);
Route::put('/room/{id}', [RoomController::class,'update']);

//Orders
Route::post('/orders', [OrderController::class,'create']); //bikin order
Route::get('/orders', [OrderController::class, 'show']);
Route::get('/orders/{id}', [OrderController::class, 'detail']);
Route::put('/orders/status/{id}', [OrderController::class, 'upstatus']); //update status
Route::put('/order/{id}', [OrderController::class,'store']);
Route::post('/orderFilter', [OrderController::class, 'orderFilter']);

//Orders Detail
Route::post('/detailorder',[OrdersDetailController::class, 'index']);

//date filter
Route::post('/datefilter',[OrdersDetailController::class, 'index']);
Route::post('/checkorder',[OrdersDetailController::class, 'checkorder']);