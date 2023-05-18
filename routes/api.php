<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
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



Route::post('/login', [AuthController::class,'login']);
Route::post('/register', [AuthController::class,'register']);
 Route::post('/logout', [AuthController::class,'logout']);

Route::get('/user/index', [UserController::class,'index']);  
Route::post('/user/create', [UserController::class,'create']);  
Route::post('/user/update', [UserController::class,'update']);  
//Route::post('/user/edit', [UserController::class,'edit']);  
Route::post('/user/delete', [UserController::class,'delete']);  

