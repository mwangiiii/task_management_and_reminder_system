<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return view('welcome');
});






    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update'); //post ya kupdate
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/storing/tasks', [TaskController::class,'store'])->name('tasks.store');// to create
    Route::patch('/update/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update'); //to update

    Route::get('/creating/task/form', [TaskController::class, 'index'])
     ->name('creating-form'); //to display form

     Route::get('/tasks/{id}/edit', [TaskController::class, 'displayUpdateForm'])->name('tasks.edit'); //accessing form ya to update one item

     Route::get('viewing/all/tasks', [TaskController::class, 'viewAllTasks'])->name('viewing-all-tasks'); //listing all tasks 

     Route::delete('delete/task/{id}', [TaskController::class, 'destroy'])->name('task.remove'); //deleting tasks.




     Route::get('/tasks/{parentTaskId}/create-child', [TaskController::class, 'createChild'])
     ->name('tasks.createChild');

     Route::get('/view/tasks/{id}', [TaskController::class, 'showOneTask'])->name('tasks.showOneTask');



    //listinga ll tasks by all users

    Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show'); //listing one tasks for a user
    

  

  




Route::get('/tasks{id}',[TaskController::class, 'list'])->name('task-mail-info');

// Route::get('/tasks/details', [TaskController::class, 'list']);


Route::get('/dashboard/create/task', [TaskController::class,'index'])->name('dashboard');



    





Route::get('/test-route',  [TaskController::class,'index']);

require __DIR__.'/auth.php';