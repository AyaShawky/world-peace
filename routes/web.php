<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PosterController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/user', [UserController::class, 'index']);
Route::get('/user/{id}', [UserController::class, 'show']);
Route::get('/users', [UserController::class, 'create']);
Route::get('/user/{id}/delete', [UserController::class, 'destroy']);
Route::get('/user/{id}/update', [UserController::class, 'update']);

Route::get('/posts', [PosterController::class, 'index']);
Route::get('/post', [PosterController::class, 'create']);
Route::get('/post/{id}', [PosterController::class, 'show']);
Route::get('/post/{id}/delete', [PosterController::class, 'destroy']);
Route::get('/post/{id}/update', [PosterController::class, 'update']);


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
