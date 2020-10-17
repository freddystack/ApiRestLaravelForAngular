<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProuctsController;
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
   // USERS
   Route::post('register', [UserController::class, 'register']);
  Route::get('users', [UserController::class, 'getUsers']);
  Route::get('user/{name}' , [UserController::class, 'getUser']);
  Route::put('user/{id}' , [UserController::class, 'updateUser']);
  Route::delete('user/{id}' , [UserController::class, 'deleteUser']);

  // PRODUCTS
  Route::apiResource('products', ProuctsController::class);
  Route::get('products/{name}', [ProuctsController::class, 'show']);

  // CATEGORIES
  Route::apiResource('categories' , CategoryController::class);
  Route::get('categories/{name}', [CategoryController::class , 'show']);
 });



 Route::group(['prefix' => 'reception' , 'middleware' => ['token.verify', 'reception.verify']] , function(){
    Route::get('products', [ProuctsController::class , 'index']);
    Route::get('categories/{name}', [CategoryController::class , 'show']);
    Route::get('categories', [CategoryController::class , 'index']);
  });


  
  Route::group(['prefix' => 'warehouse' , 'middleware' => ['token.verify', 'warehouse.verify']] , function(){
    Route::post('products', [ProuctsController::class, 'store']);
    Route::put('products/{id}', [ProuctsController::class, 'update']);
 
  });