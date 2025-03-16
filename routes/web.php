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

    //  Route::delete('delete/task/{id}', [TaskController::class, 'destroy'])->name('task.remove');

    Route::post('/tasks/{id}/revive', [TaskController::class, 'revive'])->name('tasks.revive');

// Routes for filtering tasks
Route::post('/tasks/filter-by-date', [App\Http\Controllers\TaskController::class, 'searchTask'])->name('tasks.filterByDate');
Route::post('/tasks/filter-by-status', [App\Http\Controllers\TaskController::class, 'searchTask'])->name('tasks.filterByStatus');
Route::post('/tasks/search-by-name', [App\Http\Controllers\TaskController::class, 'searchTask'])->name('tasks.searchByName');

Route::post('/tasks/{parentTaskId}/update-budget', [TaskController::class, 'updateBudget'])
    ->name('user-updating-budget');


    Route::delete('/tasks/{id}/delete-child-tasks', [TaskController::class, 'deleteChildTasks'])->name('delete-child-tasks');
    Route::get('/tasks/filter', [TaskController::class, 'filterTasks'])->name('tasks.filter');





     Route::get('/tasks/{parentTaskId}/create-child', [TaskController::class, 'createChild'])
     ->name('tasks.createChild');

     Route::get('/view/tasks/{id}', [TaskController::class, 'showOneTask'])->name('tasks.showOneTask');



    //listinga ll tasks by all users

    Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show'); //listing one tasks for a user
    Route::get('/show/completed/tasks', [TaskController::class, 'completedTasks'])->name('showing-completed-tasks');
    

  

  




Route::get('/tasks{id}',[TaskController::class, 'list'])->name('task-mail-info');

// Route::get('/tasks/details', [TaskController::class, 'list']);


Route::get('/dashboard/create/task', [TaskController::class,'index'])->name('dashboard');



    





Route::get('/test-route',  [TaskController::class,'index']);

require __DIR__.'/auth.php';