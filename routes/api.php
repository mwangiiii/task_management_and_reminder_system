<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/task-options', [TaskController::class, 'fetchOptions']);
Route::post('/tasks', [TaskController::class, 'store']);
Route::post('/updateTasks{id}', [TaskController::class, 'update']);
