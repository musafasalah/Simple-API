<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//CRUd
Route::get('/categories',[CategoryController::class ,"all"]);
Route::get('/categories/show/{id}',[CategoryController::class ,"show"]);
Route::post('/categories/create',[CategoryController::class ,"store"]);
Route::put('/categories/update/{id}',[CategoryController::class ,"update"]);
Route::delete('/categories/delete/{id}',[CategoryController::class ,"delete"]);

//Auth
Route::post('/register',[AuthController::class , "register"]);
Route::post('/login',[AuthController::class , "login"]);
Route::post('/logout',[AuthController::class , "logout"]);

//search
Route::get('/search/{title}',[CategoryController::class , 'search']);
