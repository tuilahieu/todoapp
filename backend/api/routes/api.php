<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoController;
use App\Http\Middleware\CheckTodoOwner;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function() {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get( '/logout', [AuthController::class, 'logout'])->middleware(['auth:sanctum']);
    Route::get( '/profile', [AuthController::class, 'profile'])->middleware(['auth:sanctum']);
});

Route::prefix('todos')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/', [TodoController::class, 'index']);          // GET /todos
    Route::get('/{id}', [TodoController::class, 'show'])->middleware(CheckTodoOwner::class); // GET /todos/{id}
    Route::post('/', [TodoController::class, 'store']);         // POST /todos
    Route::put('/{id}', [TodoController::class, 'update'])->middleware(CheckTodoOwner::class);  // PUT /todos/{id}
    Route::delete('/{id}', [TodoController::class, 'destroy'])->middleware(CheckTodoOwner::class); // DELETE /todos/{id}
});