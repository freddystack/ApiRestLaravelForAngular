<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

/* Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
 */

 Route::post('login', [UserController::class, 'login']);

 
 Route::group(['prefix' => 'admin' , 'middleware' => ['token.verify', 'admin.verify']] , function(){
  Route::post('register', [UserController::class, 'register']);
  Route::get('users', [UserController::class, 'getUsers']);
  Route::get('user/{name}' , [UserController::class, 'getUser']);
  Route::put('user/{id}' , [UserController::class, 'updateUser']);
  Route::delete('user/{id}' , [UserController::class, 'deleteUser']);
 });

 Route::group(['prefix' => 'reception' , 'middleware' => ['token.verify', 'reception.verify']] , function(){
    Route::post('register', [UserController::class, 'register']);
 
  });
  
  Route::group(['prefix' => 'warehouse' , 'middleware' => ['token.verify', 'warehouse.verify']] , function(){
    Route::post('register', [UserController::class, 'register']);
 
  });