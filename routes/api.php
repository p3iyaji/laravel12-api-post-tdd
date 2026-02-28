<?php

use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/posts', [PostController::class, 'index'])->name('posts');
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
Route::get('/posts/{slug}', [PostController::class, 'show'])->name('posts.show');
Route::put('/posts/{slug}', [PostController::class, 'update'])->name('posts.update');
Route::delete('/posts/{slug}', [PostController::class, 'destroy'])->name('posts.destroy');
