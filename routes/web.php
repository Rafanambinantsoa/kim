<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

//User routes
Route::get('/', [UserController::class , 'showCorrectHomePage']);
Route::post('/register', [UserController::class , 'register']);
Route::post('/login', [UserController::class , 'login']);
Route::post('/logout', [UserController::class , 'logout']);

//Post routes
Route::get('/create-post' , [PostController::class , 'showCreateForm']);
Route::post('/create-post' , [PostController::class , 'storeNewPost']);
Route::get('/post/{post}' , [PostController::class , 'viewSinglePost']);