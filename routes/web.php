<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TaskController;

Route::middleware(['auth'])->group(function () {
    Route::get('/task/{id}/children', [TaskController::class, 'getChildren'])->name('task.children');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update'); //post ya kupdate
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/storing/tasks', [TaskController::class,'store'])->name('tasks.store');// to create
    Route::patch('/update/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update'); //to update
    Route::get('w', [TaskController::class, 'index'])->name('creating-form'); //to display form
    Route::get('/tasks/{id}/edit', [TaskController::class, 'displayUpdateForm'])->name('tasks.edit'); //accessing form ya to update one item
     Route::get('viewing/all/tasks', [TaskController::class, 'viewAllTasks'])->name('viewing-all-tasks'); //listing all tasks 
    //  Route::delete('delete/task/{id}', [TaskController::class, 'destroy'])->name('task.remove');
    Route::post('/tasks/{id}/revive', [TaskController::class, 'revive'])->name('tasks.revive');
    // Routes for filtering tasks
    Route::post('/tasks/filter-by-date', [App\Http\Controllers\TaskController::class, 'searchTask'])->name('tasks.filterByDate');
    Route::post('/tasks/filter-by-status', [App\Http\Controllers\TaskController::class, 'searchTask'])->name('tasks.filterByStatus');
    Route::post('/tasks/search-by-name', [App\Http\Controllers\TaskController::class, 'searchTask'])->name('tasks.searchByName');
    Route::post('/tasks/{parentTaskId}/update-budget', [TaskController::class, 'updateBudget'])->name('user-updating-budget');
    Route::delete('/tasks/{id}/delete-child-tasks', [TaskController::class, 'deleteChildTasks'])->name('delete-child-tasks');
    Route::get('/tasks/filter', [TaskController::class, 'filterTasks'])->name('tasks.filter');
    Route::get('/tasks/{parentTaskId}/create-child', [TaskController::class, 'createChild'])->name('tasks.createChild');
    Route::delete('delete/task', [TaskController::class, 'destroy'])->name('task.remove');
    Route::get('/view/tasks/{id}', [TaskController::class, 'showOneTask'])->name('tasks.showOneTask');
    //listinga ll tasks by all users
    Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show'); //listing one tasks for a user
    Route::get('/show/completed/tasks', [TaskController::class, 'completedTasks'])->name('showing-completed-tasks');
    Route::get('/tasks{id}',[TaskController::class, 'list'])->name('task-mail-info');
    // Route::get('/tasks/details', [TaskController::class, 'list']);
    Route::get('/dashboard', [TaskController::class,'viewAllTasks'])->name('dashboard');
    Route::post('/categories/store', [TaskController::class, 'storeCategory'])->name('categories.store');
    Route::post('/recurrency/store', [TaskController::class, 'storeRecurrency'])->name('recurrency.store');
    Route::post('/process-payment', [TaskController::class, 'processPayment'])->name('process-payment');
    Route::post('/task/start/{id}', [TaskController::class, 'startTask'])->name('task.start');
    Route::post('/task/update-status/{id}', [TaskController::class, 'updateStatus'])->name('task.updateStatus');
    Route::post('/tasks/{task}/update-priority', [TaskController::class, 'updatePriority'])->name('update.priority'); //updating priority 
    // Start Task
    Route::post('/tasks/{id}/start', [TaskController::class, 'startTask'])->name('tasks.start');
    // Complete Task
    Route::post('/tasks/{id}/complete', [TaskController::class, 'completeTask'])->name('tasks.complete');
    Route::get('/tasks/{task}/start', [TaskController::class, 'startTaskNow'])->name('tasks.start');
    Route::get('/tasks/{task}/extend', [TaskController::class, 'extendTask'])->name('tasks.extend');
    Route::post('/tasks/{task}/update-due-date', [TaskController::class, 'updateDueDate'])->name('tasks.update_due_date');
    Route::get('/tasks/{id}/recreate', [TaskController::class, 'recreateTask'])->name('tasks.recreate');
    Route::get('/tasks/{id}/decline', [TaskController::class, 'declineTask'])->name('tasks.decline');
    Route::get('/test-route',  [TaskController::class,'index']);
});
Route::get('/', function () {
    return view('welcome');
});

require __DIR__.'/auth.php';