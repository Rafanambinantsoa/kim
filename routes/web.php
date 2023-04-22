<?php

use App\Http\Controllers\FollowController;
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
//Amin gate pages 
Route::get('/admins-only' , function(){
    return "Only admin should see this pages ";
})->middleware('can:visitAdminPages');

//User routes
Route::get('/', [UserController::class , 'showCorrectHomePage'])->name('login');
Route::post('/register', [UserController::class , 'register']);
Route::post('/login', [UserController::class , 'login']);
Route::post('/logout', [UserController::class , 'logout'])->middleware('karimAuth');
Route::get('/manage-avatar', [UserController::class , 'showAvatarForm'])->middleware('karimAuth');
Route::post('/manage-avatar', [UserController::class , 'storeAvatar'])->middleware('karimAuth');

//Post routes
Route::get('/create-post' , [PostController::class , 'showCreateForm'])->middleware('karimAuth');
Route::post('/create-post' , [PostController::class , 'storeNewPost'])->middleware('karimAuth');
Route::get('/post/{post}' , [PostController::class , 'viewSinglePost'])->middleware('karimAuth');
Route::delete('/post/{post}' , [PostController::class , 'delete'])->middleware('can:delete,post');
Route::get('/post/{post}/edit' , [PostController::class , 'showUpdateForm'])->middleware('can:update,post');
Route::put('/post/{post}' , [PostController::class , 'actuallyUpdate'])->middleware('can:update,post');

//Follow related routes
Route::post('/create-follow/{user:username}' , [FollowController::class , 'createFollow']);
Route::post('/remove-follow/{user:username}' , [FollowController::class , 'removeFollow']);

//Profile related routes 
Route::get('/profile/{user:username}' , [UserController::class , 'profile'])->middleware('karimAuth');
Route::get('/profile/{user:username}/followers' , [UserController::class , 'profileFollowers'])->middleware('karimAuth');
Route::get('/profile/{user:username}/followings' , [UserController::class , 'profileFollowings'])->middleware('karimAuth');