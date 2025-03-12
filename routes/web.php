<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     // return view('dashboard');
//     return view('userfolder.task-form');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', [TaskController::class,'index'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/tasks', [TaskController::class,'store'])->name('tasks.store');
    Route::get('/creating/task', [TaskController::class,'index']);
    Route::get('/api/task-options', [TaskController::class, 'fetchOptions']);
    Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');

  

    Route::delete('/delete/task', [TaskController::class, 'delete'])->name('tasks.destroy');
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');


    Route::get('/tasks/{id}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
Route::patch('/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update');

Route::get('/task/details', [TaskController::class, 'list']);




    
});

require __DIR__.'/auth.php';
