<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;


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

// Post routes
Route::get('/posts', [PostController::class, 'index']); // List all posts
Route::post('/posts', [PostController::class, 'store']); // Add a new post
Route::get('/posts/{post}', [PostController::class, 'show']); // Show a specific post
Route::put('/posts/{post}', [PostController::class, 'update']); // Update a post
Route::delete('/posts/{post}', [PostController::class, 'destroy']); // Delete a post

// Comment routes
Route::get('/posts/{post}/comments', [CommentController::class, 'show']); // List comments for a post
Route::post('/posts/{post}/comments', [CommentController::class, 'store']); // Add a new comment
Route::put('/comments/{comment}', [CommentController::class, 'update']); // Update a comment
Route::delete('/comments/{comment}', [CommentController::class, 'destroy']); // Delete a comment
Route::get('/comments/{comment}', [CommentController::class, 'show']); // Show a particular Comment
Route::get('/posts/{post}/comments', [CommentController::class, 'index']); // List comments for a post
