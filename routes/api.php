<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\AuthController;

Route::apiResource('/user', UserController::class);
Route::apiResource('/area', AreaController::class);
Route::apiResource('/genre', GenreController::class);
Route::apiResource('/shop', ShopController::class);
Route::apiResource('/favorite', FavoriteController::class);
Route::apiResource('/reservation', ReservationController::class);
Route::apiResource('/evaluation', EvaluationController::class);
Route::apiResource('/job', JobController::class);

Route::group([
  'middleware' => ['auth:api'],
  'prefix' => 'auth'
], function ($router) {
  Route::post('register', [AuthController::class, 'register'])->withoutMiddleware(['auth:api']);
  Route::post('login', [AuthController::class, 'login'])->withoutMiddleware(['auth:api']);
  Route::post('logout', [AuthController::class, 'logout']);
  Route::post('refresh', [AuthController::class, 'refresh']);
  Route::get('user', [AuthController::class, 'me']);
  Route::post('update', [AuthController::class, 'update']);
  Route::post('reminder', [AuthController::class, 'reminder'])->withoutMiddleware(['auth:api']);

  Route::get('/verify/{id}', [AuthController::class, 'verify'])->withoutMiddleware(['auth:api']);
  Route::get('/reminder/{id}', [AuthController::class, 'input_password'])->withoutMiddleware(['auth:api']);;
  Route::post('/password_change', [AuthController::class, 'password_change'])->withoutMiddleware(['auth:api']);

  Route::post('email', [AuthController::class, 'email']);
  Route::get('users', [AuthController::class, 'users']);
  Route::post('add_manager', [AuthController::class, 'add_manager']);
  Route::post('change_authority', [AuthController::class, 'change_authority']);
  Route::post('add_shop', [AuthController::class, 'add_shop']);
  Route::post('add_shop_data', [AuthController::class, 'add_shop_data']);
  Route::get('test', [AuthController::class, 'testData']);
  Route::post('test', [AuthController::class, 'test']);
});
