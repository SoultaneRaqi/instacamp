<?php


use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;



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

// Set homepage to show posts feed (require authentication)

Route::get(uri: '/', action: [PostController::class, 'index'])
    ->middleware('auth')
    ->name('home'); 

Route::get(uri: '/home', action: function () {
    return redirect(to: '/');
})->name('home');

Auth::routes();

//Post routes
Route::get(uri: '/posts', action: [PostController::class, 'index'])->name('posts.index');
Route::get(uri: '/posts/create', action: [PostController::class, 'create'])->name('posts.create');
Route::post(uri: '/posts', action: [PostController::class, 'store'])->name('posts.store');
Route::get(uri: '/posts/{post}', action: [PostController::class, 'show'])->name('posts.show');
Route::get(uri: '/posts/{post}/edit', action: [PostController::class, 'edit'])->name('posts.edit');
Route::patch(uri: '/posts/{post}', action: [PostController::class, 'update'])->name('posts.update');
Route::delete(uri: '/posts/{post}', action: [PostController::class, 'destroy'])->name('posts.destroy');


//Profile routes
Route::get(uri: '/profile/{user}', action: [ProfileController::class, 'show'])->name('profile.show');
Route::get(uri: '/profile/{user}/edit', action: [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch(uri: '/profile/{user}', action: [ProfileController::class, 'update'])->name('profile.update');


//Like routes
Route::post(uri: '/posts/{post}/like', action: [LikeController::class, 'likePost'])->name('likes.store');
Route::post(uri: '/posts/{post}/unlike', action: [LikeController::class, 'unlikePost'])->name('likes.destroy');


//Comment routes
Route::post(uri: '/posts/{post}/comment', action: [CommentController::class, 'store'])->name('comments.store');
Route::delete(uri: '/comments/{comment}', action: [CommentController::class, 'destroy'])->name('comments.destroy');
