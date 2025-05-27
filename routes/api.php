<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ReminderController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
// You would typically add a separate login route here, e.g.:
Route::post('/login', [AuthController::class, 'login']);
Route::get('/user', [UserController::class, 'showAuthenticatedUser']);
Route::put('/user', [UserController::class, 'updateAuthenticatedUser']);
// API Resource Routes for CRUD operations on Tasks and Reminders
Route::apiResource('tasks', TaskController::class)->parameters([
    'tasks' => 'task_id' // Specify the route key name for Task model
]);
Route::apiResource('reminders', ReminderController::class)->parameters([
    'reminders' => 'reminder_id' // Specify the route key name for Reminder model
]);

// Example protected route for authenticated user (requires authentication middleware)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
