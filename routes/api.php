<?php

use App\Http\Controllers\Api\CommentsController;
use App\Http\Controllers\Api\IntroductionController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
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

Route::post('/register', [UserController::class, 'register']);

Route::post('/login', [UserController::class, 'login']);
Route::post('/forgot', [ForgotPasswordController::class,'sendResetLinkEmail']);


Route::middleware('auth:api')->group( function () {
    Route::get('/profile', [UserController::class, 'getUser']);
    Route::put('/updateProfile', [UserController::class, 'putUser']);
    Route::post('/change', [UserController::class, 'changePassword']);
    Route::post('/logout', [UserController::class, 'logout']);
    Route::post('/search/{name}',[UserController::class, 'search']);
    Route::get('/friend', [UserController::class, 'getfriend']);
    Route::delete('/friend/delete/{id}',[UserController::class,'deleteFriend']);
    Route::post('/join', [UserController::class, 'Join']);


    Route::get('/posts', [PostController::class, 'allPost']);
    Route::get('/post/{id}', [PostController::class, 'getPost']);
    Route::post('/post', [PostController::class, 'createPost']);
    Route::delete('/post/delete',[PostController::class,'deletePost']);
    Route::put('/post/{id}', [PostController::class, 'update']);

    // Route::get('/posts',[PostController::class,'allPost']);
    // Route::post('/post/comment', [PostController::class, 'comments']);

    Route::get('/comments',[CommentsController::class,'index']);
    Route::post('/comment', [CommentsController::class, 'create']);
    Route::post('/comment/delete',[CommentsController::class,'deleteComment']);
    Route::post('/like',[PostController::class,'like']);

    Route::get('/getIntro', [IntroductionController::class, 'getIntro']);
    Route::post('/intro', [IntroductionController::class, 'create']);
    Route::put('/intro/{id}', [IntroductionController::class, 'update']);
    Route::post('/intro/delete',[IntroductionController::class,'delete']);

    Route::post('/share',[PostController::class,'share']);
    Route::get('/posts/friend', [PostController::class, 'timeLine']);
    Route::get('/notificatoin', [NotificationController::class, 'allNotificaion']);
    Route::post('/notificatoin/delete',[NotificationController::class,'deleteNotificatoin']);

});


